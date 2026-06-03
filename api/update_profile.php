<?php
session_start();

header('Content-Type: application/json');
require_once '../database/users.php';
require_once '../database/connection.php';

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

if (!isset($_SESSION['userID'])) {
    echo json_encode(['success' => false, 'message' => 'Session expired. Please log in again.']);
    exit;
}

$userID = $_SESSION['userID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $bio      = trim($_POST['bio'] ?? '');

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($username)) {
        echo json_encode(['success' => false, 'message' => 'Name and Username are required.']);
        exit;
    }

    try {
        $db = getDatabaseConnection();

        if (!empty($newPassword) || !empty($currentPassword) || !empty($confirmPassword)) {

            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                echo json_encode(['success' => false, 'message' => 'To change password, please fill all password fields.']);
                exit;
            }

            if ($newPassword !== $confirmPassword) {
                echo json_encode(['success' => false, 'message' => 'New passwords do not match.']);
                exit;
            }

            $storedHash = getUserPassword($db, $userID);

            if (!$storedHash || !password_verify($currentPassword, $storedHash)) {
                echo json_encode(['success' => false, 'message' => 'Incorrect current password.']);
                exit;
            }

            updateUserPassword($db, $userID, password_hash($newPassword, PASSWORD_DEFAULT));
        }

        updateUserProfile($db, $userID, $name, $username, $bio);

        if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
            $fileExtension = strtolower(pathinfo($_FILES['profilePhoto']['name'], PATHINFO_EXTENSION));
            $newFileName   = 'avatar_' . $userID . '.' . $fileExtension;
            $dest_path     = '../img/users/' . $newFileName;

            if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $dest_path)) {
                updateUserProfilePhoto($db, $userID, $newFileName);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error uploading profile photo.']);
                exit;
            }
        }

        echo json_encode(['success' => true, 'message' => 'Profile updated successfully!']);
        exit;

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'This username or email is already in use.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
