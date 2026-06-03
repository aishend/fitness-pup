<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed.']);
    exit;
}

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Invalid request.']);
    exit;
}

$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'error' => 'Please enter a valid email address.']);
    exit;
}

require_once '../database/connection.php';
require_once '../database/users.php';

$dbh = getDatabaseConnection();

if (!emailExists($dbh, $email)) {
    echo json_encode(['ok' => false, 'error' => 'No account found with that email address.']);
    exit;
}

echo json_encode(['ok' => true]);
