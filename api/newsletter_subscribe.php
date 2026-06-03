<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../database/connection.php';
require_once '../database/newsletter.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}

$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
    exit;
}

$dbh = getDatabaseConnection();

if (isNewsletterSubscriber($dbh, $email)) {
    echo json_encode(['status' => 'already', 'message' => "You're already subscribed! But we appreciate all your interest 🐾😄"]);
} else {
    addNewsletterSubscriber($dbh, $email);
    echo json_encode(['status' => 'success', 'message' => 'Thanks for subscribing! 🎉']);
}
