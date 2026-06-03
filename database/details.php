<?php

function getClassRoster(PDO $dbh, int $classID): array {
    $stmt = $dbh->prepare("
        SELECT u.userID, u.name, u.username, u.profilePhoto, u.email,
               ce.enrollment_date, ce.status
        FROM Classes_Enrollments ce
        JOIN Users u ON ce.userID = u.userID
        WHERE ce.classID = ? AND ce.status = 'enrolled'
        ORDER BY ce.enrollment_date ASC
    ");
    $stmt->execute([$classID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getClassDetails(PDO $dbh, int $classID): array|false {
    $stmt = $dbh->prepare("
        SELECT c.*, u.name as trainerName
        FROM Classes c
        JOIN Trainers t ON c.trainerID = t.trainerID
        JOIN Users u ON t.userID = u.userID
        WHERE c.classID = ?
    ");
    $stmt->execute([$classID]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPetSessionRoster(PDO $dbh, int $sessionID): array {
    $stmt = $dbh->prepare("
        SELECT p.petID, p.name as petName, p.breed, p.age, p.vaccinated,
               u.userID, u.name as ownerName, u.username, u.profilePhoto,
               pe.enrollment_date
        FROM Pet_Enrollments pe
        JOIN Pets p ON pe.petID = p.petID
        JOIN Users u ON p.ownerID = u.userID
        WHERE pe.sessionID = ? AND pe.status = 'enrolled'
        ORDER BY pe.enrollment_date ASC
    ");
    $stmt->execute([$sessionID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPetSessionDetails(PDO $dbh, int $sessionID): array|false {
    $stmt = $dbh->prepare("
        SELECT ps.*, pr.name as roomName, u.name as trainerName
        FROM Pet_Sessions ps
        JOIN Pet_Rooms pr ON ps.pet_roomID = pr.pet_roomID
        JOIN Users u ON ps.trainerID = u.userID
        WHERE ps.sessionID = ?
    ");
    $stmt->execute([$sessionID]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>