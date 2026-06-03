<?php

function getTrainersByRole(PDO $dbh, string $role): array {
    $stmt = $dbh->prepare("
        SELECT u.userID, u.name, u.username, u.profilePhoto, u.bio, u.role,
               t.trainerID, t.specialty, t.certifications
        FROM Users u
        LEFT JOIN Trainers t ON u.userID = t.userID
        WHERE u.role = ?
        ORDER BY u.name ASC
    ");
    $stmt->execute([$role]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUpcomingClassesForTrainer(PDO $dbh, int $trainerID): array {
    $stmt = $dbh->prepare("
        SELECT name, type, start_time
        FROM Classes
        WHERE trainerID = ? AND class_status = 'upcoming'
        ORDER BY start_time ASC
        LIMIT 5
    ");
    $stmt->execute([$trainerID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>