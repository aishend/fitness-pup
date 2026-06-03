<?php
session_start();

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    header('Location: ../../manage_users.php');
    exit;
}

require_once '../../database/connection.php';
require_once '../../database/users.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../login.php');
    exit;
}

$dbh = getDatabaseConnection();
$action = $_POST['action'];
$name = $_POST['name'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

try {
    if ($action === 'create') {
        adminCreateUser($dbh, $name, $username, $email, $password, $role);
        $_SESSION['messages'][] = ['type' => 'success', 'content' => 'User created successfully!'];
    } elseif ($action === 'edit') {
        $userID = $_POST['userID'];
        adminUpdateUser($dbh, $userID, $name, $username, $email, $password, $role);
        $_SESSION['messages'][] = ['type' => 'success', 'content' => 'User updated successfully!'];
    }
} catch (PDOException $e) {
    $_SESSION['messages'][] = ['type' => 'error', 'content' => 'Database error! Username or email might already exist.'];
}

header('Location: ../../manage_users.php');
exit;