<?php

function getFilteredClasses(PDO $dbh, $type = '', $trainerID = '', $day = '', $time = '') {
    $sql = "
        SELECT
            Classes.classID,
            Classes.name,
            Classes.type,
            Classes.description,
            Classes.start_time,
            Classes.end_time,
            Classes.capacity,
            Classes.class_image,
            Users.name AS trainerName,
            Trainers.trainerID
        FROM Classes
        JOIN Trainers ON Classes.trainerID = Trainers.trainerID
        JOIN Users ON Trainers.userID = Users.userID
        WHERE Classes.class_status = 'upcoming'
    ";

    $params = [];

    if (!empty($type)) {
        $sql .= " AND Classes.type = :type";
        $params[':type'] = $type;
    }

    if (!empty($trainerID)) {
        $sql .= " AND Trainers.trainerID = :trainerID";
        $params[':trainerID'] = (int)$trainerID;
    }

    if (!empty($day)) {
        $dayMap = [
            'sunday'=>0,'monday'=>1,'tuesday'=>2,'wednesday'=>3,
            'thursday'=>4,'friday'=>5,'saturday'=>6
        ];

        if (isset($dayMap[$day])) {
            $sql .= " AND CAST(strftime('%w', Classes.start_time) AS INTEGER) = :day";
            $params[':day'] = $dayMap[$day];
        }
    }

    if (!empty($time)) {
        $hour = "CAST(strftime('%H', Classes.start_time) AS INTEGER)";

        if ($time === 'morning') {
            $sql .= " AND $hour < 12";
        } elseif ($time === 'afternoon') {
            $sql .= " AND $hour BETWEEN 12 AND 17";
        } elseif ($time === 'evening') {
            $sql .= " AND $hour > 17";
        }
    }

    $sql .= " ORDER BY Classes.start_time ASC";

    $stmt = $dbh->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTrainersForClasses(PDO $dbh) {
    $stmt = $dbh->prepare("
        SELECT Trainers.trainerID, Users.name
        FROM Trainers
        JOIN Users ON Trainers.userID = Users.userID
        WHERE Users.role = 'trainer'
        ORDER BY Users.name ASC
    ");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getClassTypesFromDB(PDO $dbh) {
    $stmt = $dbh->prepare("
        SELECT DISTINCT type FROM Classes
        WHERE class_status = 'upcoming'
        ORDER BY type ASC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getScheduleData(PDO $dbh, $userID = null, $type = '', $trainerID = '', $startDate = '', $endDate = '') {
    $sql = "
        SELECT
            C.classID,
            C.name AS title,
            C.type,
            C.start_time,
            C.end_time,
            C.capacity,
            C.trainerID,
            C.class_image,
            T.userID AS trainerUserID,
            U.name AS trainer,
            (SELECT COUNT(*) FROM Classes_Enrollments WHERE classID = C.classID AND status = 'enrolled') AS booked,
            (SELECT COUNT(*) FROM Classes_Enrollments WHERE classID = C.classID AND userID = ? AND status = 'enrolled') AS is_enrolled
        FROM Classes C
        JOIN Trainers T ON C.trainerID = T.trainerID
        JOIN Users U ON T.userID = U.userID
        WHERE C.class_status = 'upcoming'
    ";

    $params = [$userID];

    if (!empty($startDate) && !empty($endDate)) {
        $sql .= " AND C.start_time >= ? AND C.start_time <= ?";
        $params[] = $startDate;
        $params[] = $endDate;
    }

    if (!empty($type)) {
        $sql .= " AND C.type = ?";
        $params[] = $type;
    }

    if (!empty($trainerID)) {
        $sql .= " AND T.trainerID = ?";
        $params[] = $trainerID;
    }

    $sql .= " ORDER BY C.start_time ASC";

    $stmt = $dbh->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function enrollInClass(PDO $dbh, $userID, $classID) {
    $stmt = $dbh->prepare("
        INSERT INTO Classes_Enrollments (userID, classID, status)
        VALUES (?, ?, 'enrolled')
        ON CONFLICT(userID, classID) DO UPDATE SET status = 'enrolled'
    ");
    return $stmt->execute([$userID, $classID]);
}

function cancelClassEnrollment(PDO $dbh, $userID, $classID) {
    $stmt = $dbh->prepare("
        UPDATE Classes_Enrollments
        SET status = 'cancelled'
        WHERE userID = ? AND classID = ?
    ");
    return $stmt->execute([$userID, $classID]);
}

function removeClassByAdmin(PDO $dbh, $classID) {
    $stmt = $dbh->prepare("
        UPDATE Classes
        SET class_status = 'cancelled'
        WHERE classID = ?
    ");
    return $stmt->execute([$classID]);
}

function createClass(PDO $dbh, $title, $trainerID, $type, $capacity, $date, $start_time, $end_time, $class_image = null) {
    $stmt = $dbh->prepare("
        INSERT INTO Classes (name, trainerID, type, capacity, start_time, end_time, class_status, class_image)
        VALUES (?, ?, ?, ?, ?, ?, 'upcoming', ?)
    ");
    return $stmt->execute([$title, $trainerID, $type, $capacity, $date . ' ' . $start_time . ':00', $date . ' ' . $end_time . ':00', $class_image]);
}

function updateClass(PDO $dbh, $classID, $title, $trainerID, $type, $capacity, $date, $start_time, $end_time, $class_image = null) {
    if ($class_image !== null) {
        $stmt = $dbh->prepare("
            UPDATE Classes SET name = ?, trainerID = ?, type = ?, capacity = ?, start_time = ?, end_time = ?, class_image = ?
            WHERE classID = ?
        ");
        return $stmt->execute([$title, $trainerID, $type, $capacity, $date . ' ' . $start_time . ':00', $date . ' ' . $end_time . ':00', $class_image, $classID]);
    } else {
        $stmt = $dbh->prepare("
            UPDATE Classes SET name = ?, trainerID = ?, type = ?, capacity = ?, start_time = ?, end_time = ?
            WHERE classID = ?
        ");
        return $stmt->execute([$title, $trainerID, $type, $capacity, $date . ' ' . $start_time . ':00', $date . ' ' . $end_time . ':00', $classID]);
    }
}

function getTrainerIDByUserID(PDO $dbh, $userID) {
    $stmt = $dbh->prepare("SELECT trainerID FROM Trainers WHERE userID = ?");
    $stmt->execute([$userID]);
    $row = $stmt->fetch();
    return $row ? (int)$row['trainerID'] : null;
}

function getClassOwnerTrainerID(PDO $dbh, int $classID): ?int {
    $stmt = $dbh->prepare("SELECT trainerID FROM Classes WHERE classID = ?");
    $stmt->execute([$classID]);
    $row = $stmt->fetch();
    return $row ? (int)$row['trainerID'] : null;
}
?>