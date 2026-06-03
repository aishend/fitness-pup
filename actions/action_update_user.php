<?php
session_start();

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    header('Location: ../manage_users.php');
    exit;
}

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../database/connection.php';
require_once '../database/users.php';

$userID   = isset($_POST['userID'])   ? (int)$_POST['userID']        : null;
$name     = isset($_POST['name'])     ? trim($_POST['name'])          : null;
$username = isset($_POST['username']) ? trim($_POST['username'])      : null;
$email    = isset($_POST['email'])    ? trim($_POST['email'])         : null;
$bio      = isset($_POST['bio'])      ? trim($_POST['bio'])           : null;

if ($userID && $name && $username && $email) {
    try {
        $dbh = getDatabaseConnection();

        if (checkUsernameEmailConflict($dbh, $username, $email, $userID)) {
            $_SESSION['error'] = 'Username or email already exists.';
        } else {
            basicUpdateUser($dbh, $userID, $name, $username, $email, $bio);
            $_SESSION['success'] = 'User updated successfully.';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error updating user: ' . $e->getMessage();
    }
} else {
    $_SESSION['error'] = 'Please fill in all required fields.';
}

header('Location: ../manage_users.php');
exit;
