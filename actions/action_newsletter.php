<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../database/connection.php';
require_once '../database/newsletter.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = 'Please enter a valid email address.';
    header('Location: ../index.php');
    exit;
}

$dbh = getDatabaseConnection();

if (isNewsletterSubscriber($dbh, $email)) {
    $_SESSION['success_message'] = "You're already subscribed! But we appreciate all your interest 🐾😄";
} else {
    addNewsletterSubscriber($dbh, $email);
    $_SESSION['success_message'] = 'Thanks for subscribing!';
}

header('Location: ../index.php');
exit;
