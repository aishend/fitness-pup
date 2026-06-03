<?php
session_start();

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    header('Location: ../../manage_users.php');
    exit;
}

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

require_once '../../database/connection.php';
require_once '../../database/admin.php';

$userID = isset($_POST['userID']) ? (int)$_POST['userID'] : null;

if ($userID && $userID !== (int)$_SESSION['userID']) {
    $dbh = getDatabaseConnection();
    deleteUser($dbh, $userID);
    $_SESSION['success'] = 'User deleted.';
}

header('Location: ../../manage_users.php');
exit;
