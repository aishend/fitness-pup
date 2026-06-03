<?php

function getMemberCount(PDO $dbh) {
    $stmt = $dbh->query("SELECT COUNT(*) FROM Users WHERE role = 'member'");
    return (int)$stmt->fetchColumn();
}

function getTrainerCount(PDO $dbh) {
    $stmt = $dbh->query("SELECT COUNT(*) FROM Users WHERE role IN ('trainer', 'pet-trainer')");
    return (int)$stmt->fetchColumn();
}

function getUpcomingClassCount(PDO $dbh) {
    $stmt = $dbh->query("SELECT COUNT(*) FROM Classes WHERE class_status = 'upcoming'");
    return (int)$stmt->fetchColumn();
}

function getPetCount(PDO $dbh) {
    $stmt = $dbh->query("SELECT COUNT(*) FROM Pets");
    return (int)$stmt->fetchColumn();
}

function getAvailableEquipmentCount(PDO $dbh) {
    $stmt = $dbh->query("SELECT COUNT(*) FROM Equipment WHERE status = 'available'");
    return (int)$stmt->fetchColumn();
}

function getTotalEquipmentCount(PDO $dbh) {
    $stmt = $dbh->query("SELECT COUNT(*) FROM Equipment");
    return (int)$stmt->fetchColumn();
}

function getAdminCount(PDO $dbh) {
    $stmt = $dbh->query("SELECT COUNT(*) FROM Users WHERE role = 'admin'");
    return (int)$stmt->fetchColumn();
}
?>