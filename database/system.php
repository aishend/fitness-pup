<?php

function getSqliteVersion(PDO $dbh): string {
    return $dbh->query('SELECT sqlite_version()')->fetchColumn();
}

function getRecentActivityLogs(PDO $dbh): array {
    $stmt = $dbh->query("
        SELECT 'Class Booking' AS action, u.name AS user_name, c.name AS target_name, ce.enrollment_date AS log_date
        FROM Classes_Enrollments ce
        JOIN Users u ON ce.userID = u.userID
        JOIN Classes c ON ce.classID = c.classID
        UNION ALL
        SELECT 'Pet Booking' AS action, u.name AS user_name, ps.title AS target_name, pe.enrollment_date AS log_date
        FROM Pet_Enrollments pe
        JOIN Pets p ON pe.petID = p.petID
        JOIN Users u ON p.ownerID = u.userID
        JOIN Pet_Sessions ps ON pe.sessionID = ps.sessionID
        ORDER BY log_date DESC
        LIMIT 15
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>