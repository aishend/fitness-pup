<?php

function getPetRooms(PDO $dbh) {
    $stmt = $dbh->prepare("SELECT * FROM Pet_Rooms ORDER BY name ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRoomTrainers(PDO $dbh) {
    $stmt = $dbh->prepare("SELECT userID as trainerID, name FROM Users WHERE role = 'pet-trainer' ORDER BY name ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPetScheduleData(PDO $dbh, $userID, $roomID, $trainerID, $startDate, $endDate) {
    $sql = "
        SELECT
            PS.sessionID as reservationID,
            PS.title,
            PS.start_time,
            PS.end_time,
            PS.capacity,
            PS.pet_roomID,
            PS.trainerID,
            PR.name as roomName,
            U.name as trainer,
            (SELECT COUNT(*) FROM Pet_Enrollments PE WHERE PE.sessionID = PS.sessionID AND PE.status = 'enrolled') as booked,
            (SELECT COUNT(*) FROM Pet_Enrollments PE
             JOIN Pets P ON PE.petID = P.petID
             WHERE PE.sessionID = PS.sessionID AND P.ownerID = ? AND PE.status = 'enrolled') as is_enrolled
        FROM Pet_Sessions PS
        JOIN Pet_Rooms PR ON PS.pet_roomID = PR.pet_roomID
        JOIN Users U ON PS.trainerID = U.userID
        WHERE PS.start_time >= ? AND PS.start_time <= ?
    ";

    $params = [$userID ?? 0, $startDate, $endDate];

    if (!empty($roomID)) {
        $sql .= " AND PS.pet_roomID = ?";
        $params[] = $roomID;
    }

    if (!empty($trainerID)) {
        $sql .= " AND PS.trainerID = ?";
        $params[] = $trainerID;
    }

    $sql .= " ORDER BY PS.start_time ASC";

    $stmt = $dbh->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEnrolledPetsBySession(PDO $dbh, int $userID, array $sessionIDs): array {
    if (empty($sessionIDs)) return [];
    $placeholders = implode(',', array_fill(0, count($sessionIDs), '?'));
    $stmt = $dbh->prepare("
        SELECT PE.sessionID, PE.petID
        FROM Pet_Enrollments PE
        JOIN Pets P ON PE.petID = P.petID
        WHERE P.ownerID = ? AND PE.sessionID IN ($placeholders) AND PE.status = 'enrolled'
    ");
    $stmt->execute(array_merge([$userID], $sessionIDs));
    $map = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $map[$row['sessionID']][] = (int)$row['petID'];
    }
    return $map;
}

function getUserEnrolledPetIDsForSession(PDO $dbh, int $userID, int $sessionID, array $userPetIDs): array {
    if (empty($userPetIDs)) return [];
    $placeholders = implode(',', array_fill(0, count($userPetIDs), '?'));
    $stmt = $dbh->prepare("
        SELECT petID FROM Pet_Enrollments
        WHERE sessionID = ? AND petID IN ($placeholders) AND status = 'enrolled'
    ");
    $stmt->execute(array_merge([$sessionID], $userPetIDs));
    return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'petID');
}

function enrollPetInSession(PDO $dbh, $sessionID, $petID) {
    $check = $dbh->prepare("
        SELECT PS.capacity,
               (SELECT COUNT(*) FROM Pet_Enrollments PE WHERE PE.sessionID = PS.sessionID AND PE.status = 'enrolled') as booked
        FROM Pet_Sessions PS WHERE PS.sessionID = ?
    ");
    $check->execute([$sessionID]);
    $row = $check->fetch(PDO::FETCH_ASSOC);
    if ($row && $row['booked'] >= $row['capacity']) {
        throw new Exception('Session is at full capacity.');
    }

    $stmt = $dbh->prepare("
        INSERT INTO Pet_Enrollments (sessionID, petID, status)
        VALUES (?, ?, 'enrolled')
        ON CONFLICT(sessionID, petID) DO UPDATE SET status = 'enrolled'
    ");
    return $stmt->execute([$sessionID, $petID]);
}

function cancelPetEnrollment(PDO $dbh, $sessionID, $petID) {
    $stmt = $dbh->prepare("
        UPDATE Pet_Enrollments
        SET status = 'cancelled'
        WHERE sessionID = ? AND petID = ?
    ");
    return $stmt->execute([$sessionID, $petID]);
}

function createPetSession(PDO $dbh, $title, $pet_roomID, $trainerID, $capacity, $date, $start_time, $end_time) {
    $stmt = $dbh->prepare("
        INSERT INTO Pet_Sessions (title, pet_roomID, trainerID, capacity, start_time, end_time)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    return $stmt->execute([$title, $pet_roomID, $trainerID, $capacity, $date . ' ' . $start_time . ':00', $date . ' ' . $end_time . ':00']);
}

function updatePetSession(PDO $dbh, $sessionID, $title, $pet_roomID, $trainerID, $capacity, $date, $start_time, $end_time) {
    $stmt = $dbh->prepare("
        UPDATE Pet_Sessions
        SET title = ?, pet_roomID = ?, trainerID = ?, capacity = ?, start_time = ?, end_time = ?
        WHERE sessionID = ?
    ");
    return $stmt->execute([$title, $pet_roomID, $trainerID, $capacity, $date . ' ' . $start_time . ':00', $date . ' ' . $end_time . ':00', $sessionID]);
}

function deletePetSession(PDO $dbh, $sessionID) {
    $stmt = $dbh->prepare("DELETE FROM Pet_Sessions WHERE sessionID = ?");
    return $stmt->execute([$sessionID]);
}

function getPetSessionOwnerTrainerID(PDO $dbh, int $sessionID): ?int {
    $stmt = $dbh->prepare("SELECT trainerID FROM Pet_Sessions WHERE sessionID = ?");
    $stmt->execute([$sessionID]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (int)$row['trainerID'] : null;
}
