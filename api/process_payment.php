<?php
session_start();
header('Content-Type: application/json');
require_once '../database/connection.php';
require_once '../database/users.php';

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

if (!isset($_SESSION['userID'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['userID'];
    $planID = intval($_POST['planID'] ?? 0);

    if ($planID <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid plan selection.']);
        exit;
    }

    try {
        $db = getDatabaseConnection();

        updateUserPlan($db, $userID, $planID);

        echo json_encode(['success' => true, 'message' => 'Success']);
        exit;
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}