<?php

function getMemberPets(PDO $dbh, int $userID): array {
    $stmt = $dbh->prepare("
        SELECT * FROM Pets WHERE ownerID = ?
    ");
    $stmt->execute([$userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function setSkipPetPrompt(PDO $dbh, int $userID): void {
    $stmt = $dbh->prepare("
        UPDATE Users SET skip_pet_prompt = 1 WHERE userID = ?
    ");
    $stmt->execute([$userID]);
}

function registerPet(PDO $dbh, int $ownerID, string $name, string $breed, int $age, int $vaccinated, string $photo): void {
    $stmt = $dbh->prepare("
        INSERT INTO Pets (name, breed, age, ownerID, vaccinated, photo)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$name, $breed, $age, $ownerID, $vaccinated, $photo]);
}

function getPetsByOwner(PDO $dbh, int $userID): array {
    $stmt = $dbh->prepare("SELECT * FROM Pets WHERE ownerID = ?");
    $stmt->execute([$userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPetById(PDO $dbh, int $petID): array|false {
    $stmt = $dbh->prepare("SELECT * FROM Pets WHERE petID = ?");
    $stmt->execute([$petID]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updatePet(PDO $dbh, int $petID, string $name, string $breed, int $age, int $vaccinated, string $photo): void {
    $stmt = $dbh->prepare("UPDATE Pets SET name = ?, breed = ?, age = ?, vaccinated = ?, photo = ? WHERE petID = ?");
    $stmt->execute([$name, $breed, $age, $vaccinated, $photo, $petID]);
}

function getOwnerPetIDs(PDO $dbh, int $ownerID): array {
    $stmt = $dbh->prepare("SELECT petID FROM Pets WHERE ownerID = ?");
    $stmt->execute([$ownerID]);
    return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'petID');
}

function getPetUpcomingSessions(PDO $dbh, int $petID): array {
    $stmt = $dbh->prepare("
        SELECT PS.sessionID, PS.title, PS.start_time, PS.end_time,
               PR.name as roomName, U.name as trainerName
        FROM Pet_Sessions PS
        JOIN Pet_Enrollments PE ON PS.sessionID = PE.sessionID
        JOIN Pet_Rooms PR ON PS.pet_roomID = PR.pet_roomID
        JOIN Users U ON PS.trainerID = U.userID
        WHERE PE.petID = ? AND PE.status = 'enrolled' AND PS.start_time >= datetime('now')
        ORDER BY PS.start_time ASC
    ");
    $stmt->execute([$petID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>