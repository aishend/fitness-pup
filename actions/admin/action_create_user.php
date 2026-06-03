<?php
session_start();

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    header('Location: ../../create_user.php');
    exit;
}

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

require_once '../../database/connection.php';
require_once '../../database/users.php';

$name     = isset($_POST['name'])     ? trim($_POST['name'])     : null;
$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$email    = isset($_POST['email'])    ? trim($_POST['email'])    : null;
$password = isset($_POST['password']) ? $_POST['password']       : null;
$role     = isset($_POST['role'])     ? trim($_POST['role'])     : null;
$bio      = isset($_POST['bio'])      ? trim($_POST['bio'])      : null;

$validRoles = ['member', 'trainer', 'pet-trainer'];
$errors     = [];

if (!$name || strlen($name) < 2) {
    $errors[] = 'Name must be at least 2 characters.';
}

if (!$username || strlen($username) < 3) {
    $errors[] = 'Username must be at least 3 characters.';
}

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required.';
}

if (!$password || strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters.';
}

if (!$role || !in_array($role, $validRoles)) {
    $errors[] = 'Invalid role selected.';
}

if ($errors) {
    $_SESSION['error'] = implode(' | ', $errors);
    header('Location: ../../create_user.php');
    exit;
}

try {
    $dbh = getDatabaseConnection();

    if (userExists($dbh, $username, $email)) {
        $_SESSION['error'] = 'Username or email already exists.';
        header('Location: ../../create_user.php');
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    insertUser($dbh, $name, $username, $email, $hashedPassword, $role);

    $newUser = getUserByUsername($dbh, $username);
    if ($role === 'member' && $newUser) {
        createMemberStats($dbh, $newUser['userID']);
    }

    $_SESSION['success'] = "User '$name' created successfully.";
    header('Location: ../../manage_users.php');
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = 'Error creating user: ' . $e->getMessage();
    header('Location: ../../create_user.php');
    exit;
}
