<?php
session_start();

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    header('Location: ../profile.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../profile.php');
    exit;
}

if (!isset($_SESSION['userID']) || ($_SESSION['role'] ?? '') !== 'member') {
    header('Location: ../login.php');
    exit;
}

require_once '../database/connection.php';
require_once '../database/reviews.php';

$dbh = getDatabaseConnection();
$userID = (int)$_SESSION['userID'];
$action = $_POST['action'] ?? '';

try {
    if ($action === 'submit_review') {
        $enrollmentID = isset($_POST['enrollmentID']) ? (int)$_POST['enrollmentID'] : 0;
        $rating       = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
        $comment      = trim($_POST['comment'] ?? '');

        if ($enrollmentID <= 0 || $rating < 1 || $rating > 5) {
            $_SESSION['error_message'] = "Invalid review data.";
        } elseif (strlen($comment) > 4000) {
            $_SESSION['error_message'] = "Comment must be 1000 characters or fewer.";
        } elseif (!canUserReviewEnrollment($dbh, $userID, $enrollmentID)) {
            $_SESSION['error_message'] = "You cannot review this class.";
        } else {
            submitReview($dbh, $enrollmentID, $rating, $comment);
            $_SESSION['success_message'] = "Review submitted! Thank you for your feedback.";
        }

    } elseif ($action === 'delete_review') {
        $reviewID = isset($_POST['reviewID']) ? (int)$_POST['reviewID'] : 0;

        if ($reviewID <= 0) {
            $_SESSION['error_message'] = "Invalid review.";
        } else {
            deleteReview($dbh, $reviewID, $userID);
            $_SESSION['success_message'] = "Review deleted.";
        }
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = "An error occurred while processing your request.";
}

header("Location: ../profile.php");
exit;
?>
