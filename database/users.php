<?php

function getUserByUsernameOrEmail(PDO $dbh, $login) {
    $stmt = $dbh->prepare('SELECT * FROM Users WHERE username = ? OR email = ?');
    $stmt->execute(array($login, $login));
    return $stmt->fetch();
}

function insertUser(PDO $dbh, $name, $username, $email, $hashedPassword, $role) {
    $stmt = $dbh->prepare('INSERT INTO Users (name, username, email, password, role) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute(array($name, $username, $email, $hashedPassword, $role));
}

function getUserProfileById(PDO $dbh, $userID) {
    $stmt = $dbh->prepare("SELECT * FROM Users WHERE userID = ?");
    $stmt->execute([$userID]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserProfileAndStatsById(PDO $dbh, $userID) {
    $stmt = $dbh->prepare("
        SELECT Users.*, MemberStats.workout_count, MemberStats.weekly_streak
        FROM Users
        LEFT JOIN MemberStats ON Users.userID = MemberStats.userID
        WHERE Users.userID = ?
    ");
    $stmt->execute([$userID]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateUserProfile(PDO $dbh, $userID, $name, $username, $bio) {
    $stmt = $dbh->prepare("UPDATE Users SET name = ?, username = ?, bio = ? WHERE userID = ?");
    return $stmt->execute([$name, $username, $bio, $userID]);
}

function updateUserProfilePhoto(PDO $dbh, $userID, $profilePhoto) {
    $stmt = $dbh->prepare("UPDATE Users SET profilePhoto = ? WHERE userID = ?");
    return $stmt->execute([$profilePhoto, $userID]);
}

function getPetTrainers(PDO $dbh) {
    $stmt = $dbh->prepare("SELECT name, profilePhoto, bio FROM Users WHERE role = 'pet-trainer'");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getUserBadges(PDO $dbh, $userID) {
    $stmt = $dbh->prepare("
        SELECT Badges.* FROM Badges
        JOIN UserBadges ON Badges.badgeID = UserBadges.badgeID
        WHERE UserBadges.userID = ?
    ");
    $stmt->execute([$userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateTrainerData($db, $userID, $specialty, $certifications) {
    $stmt = $db->prepare("SELECT trainerID FROM Trainers WHERE userID = ?");
    $stmt->execute([$userID]);
    $trainer = $stmt->fetch();

    if ($trainer) {
        $updateStmt = $db->prepare("
            UPDATE Trainers
            SET specialty = ?, certifications = ?
            WHERE userID = ?
        ");
        $updateStmt->execute([$specialty, $certifications, $userID]);
    } else {
        $insertStmt = $db->prepare("
            INSERT INTO Trainers (userID, specialty, certifications)
            VALUES (?, ?, ?)
        ");
        $insertStmt->execute([$userID, $specialty, $certifications]);
    }
}

function userExists(PDO $dbh, $username, $email) {
    $stmt = $dbh->prepare("SELECT userID FROM Users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    return $stmt->fetch();
}

function emailExists(PDO $dbh, string $email): bool {
    $stmt = $dbh->prepare("SELECT 1 FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    return (bool)$stmt->fetch();
}

function getUserByUsername(PDO $dbh, $username) {
    $stmt = $dbh->prepare("SELECT userID FROM Users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function createMemberStats(PDO $dbh, $userID) {
    $stmt = $dbh->prepare("INSERT INTO MemberStats (userID) VALUES (?)");
    return $stmt->execute([$userID]);
}

function adminCreateUser(PDO $dbh, $name, $username, $email, $password, $role) {
    $options = ['cost' => 12];
    $hash = password_hash($password, PASSWORD_DEFAULT, $options);
    $stmt = $dbh->prepare('INSERT INTO Users (name, username, email, password, role) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$name, $username, $email, $hash, $role]);

    $userID = $dbh->lastInsertId();
    if ($role === 'member') {
        $stmtStats = $dbh->prepare("INSERT INTO MemberStats (userID) VALUES (?)");
        $stmtStats->execute([$userID]);
    }
}

function adminUpdateUser(PDO $dbh, $userID, $name, $username, $email, $password, $role) {
    if (!empty($password)) {
        $options = ['cost' => 12];
        $hash = password_hash($password, PASSWORD_DEFAULT, $options);
        $stmt = $dbh->prepare('UPDATE Users SET name = ?, username = ?, email = ?, password = ?, role = ? WHERE userID = ?');
        $stmt->execute([$name, $username, $email, $hash, $role, $userID]);
    } else {
        $stmt = $dbh->prepare('UPDATE Users SET name = ?, username = ?, email = ?, role = ? WHERE userID = ?');
        $stmt->execute([$name, $username, $email, $role, $userID]);
    }
}

function getTrainerData(PDO $db, $userID) {
    $stmt = $db->prepare("SELECT * FROM Trainers WHERE userID = ?");
    $stmt->execute([$userID]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getMemberStatsData(PDO $db, $userID) {
    $stmt = $db->prepare("SELECT * FROM MemberStats WHERE userID = ?");
    $stmt->execute([$userID]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getFrequentedClasses(PDO $db, $userID, $limit = 4) {
    $stmt = $db->prepare("
        SELECT Classes.name as class_name, COUNT(Classes_Enrollments.enrollmentID) as frequency
        FROM Classes_Enrollments
        JOIN Classes ON Classes_Enrollments.classID = Classes.classID
        WHERE Classes_Enrollments.userID = ? AND Classes_Enrollments.status = 'enrolled'
        GROUP BY Classes.name
        ORDER BY frequency DESC
        LIMIT ?
    ");
    $stmt->execute([$userID, $limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUpcomingTrainerClasses(PDO $db, $trainerName, $trainerID) {
    $stmt = $db->prepare("
        SELECT Classes.classID, Classes.name, Classes.type, Classes.capacity,
               Classes.start_time AS date, Classes.end_time, Classes.class_image,
               ((strftime('%s', Classes.end_time) - strftime('%s', Classes.start_time)) / 60) AS duration,
               ? AS trainerName
        FROM Classes
        WHERE Classes.trainerID = ? AND Classes.class_status = 'upcoming'
        ORDER BY Classes.start_time ASC
    ");
    $stmt->execute([$trainerName, $trainerID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUpcomingPetTrainerSessions(PDO $db, $trainerName, $userID) {
    $stmt = $db->prepare("
        SELECT ps.sessionID, ps.title as name, ps.start_time as date,
               60 as duration, ? as trainerName, pr.name as roomName
        FROM Pet_Sessions ps
        JOIN Pet_Rooms pr ON ps.pet_roomID = pr.pet_roomID
        WHERE ps.trainerID = ? AND ps.start_time >= datetime('now')
        ORDER BY ps.start_time ASC
    ");
    $stmt->execute([$trainerName, $userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserPassword(PDO $dbh, int $userID): string|false {
    $stmt = $dbh->prepare("SELECT password FROM Users WHERE userID = ?");
    $stmt->execute([$userID]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['password'] : false;
}

function updateUserPassword(PDO $dbh, int $userID, string $hashedPassword): void {
    $stmt = $dbh->prepare("UPDATE Users SET password = ? WHERE userID = ?");
    $stmt->execute([$hashedPassword, $userID]);
}

function checkUsernameEmailConflict(PDO $dbh, string $username, string $email, int $excludeUserID): bool {
    $stmt = $dbh->prepare("SELECT userID FROM Users WHERE (username = ? OR email = ?) AND userID != ?");
    $stmt->execute([$username, $email, $excludeUserID]);
    return (bool)$stmt->fetch();
}

function basicUpdateUser(PDO $dbh, int $userID, string $name, string $username, string $email, ?string $bio): void {
    $stmt = $dbh->prepare("UPDATE Users SET name = ?, username = ?, email = ?, bio = ? WHERE userID = ?");
    $stmt->execute([$name, $username, $email, $bio, $userID]);
}

function clearSkipPetPrompt(PDO $dbh, int $userID): void {
    $stmt = $dbh->prepare("UPDATE Users SET skip_pet_prompt = 0 WHERE userID = ?");
    $stmt->execute([$userID]);
}

function getUpcomingMemberClasses(PDO $db, $userID) {
    $stmt = $db->prepare("
        SELECT Classes.classID, Classes.name, Classes.start_time as date,
               45 as duration, Users.name as trainerName
            FROM Classes
            JOIN Trainers ON Classes.trainerID = Trainers.trainerID
            JOIN Users ON Trainers.userID = Users.userID
            JOIN Classes_Enrollments ON Classes.classID = Classes_Enrollments.classID
            WHERE Classes_Enrollments.userID = ? AND Classes_Enrollments.status = 'enrolled'
            ORDER BY Classes.start_time ASC
    ");
    $stmt->execute([$userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateUserPlan(PDO $dbh, int $userID, int $planID): void {
    $stmt = $dbh->prepare("UPDATE Users SET planID = ? WHERE userID = ?");
    $stmt->execute([$planID, $userID]);
}

?>