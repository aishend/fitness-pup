<?php
session_start();

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    header('Location: ../profile.php');
    exit;
}

require_once '../database/connection.php';
require_once '../database/users.php';

if (!isset($_SESSION['userID'])) {
    header('Location: ../login.php');
    exit;
}

$userID = $_SESSION['userID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $bio = trim($_POST['bio'] ?? '');

    if (empty($name) || empty($username)) {
        $_SESSION['messages'][] = ['type' => 'error', 'content' => 'Name and Username are required.'];
        header('Location: ../profile.php');
        exit;
    }

    try {
        $db = getDatabaseConnection();

        updateUserProfile($db, $userID, $name, $username, $bio);
        $_SESSION['name'] = $name;

        if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
            $fileExtension = strtolower(pathinfo($_FILES['profilePhoto']['name'], PATHINFO_EXTENSION));
            $newFileName = 'avatar_' . $userID . '_' . time() . '.' . $fileExtension;
            $dest_path = '../img/users/' . $newFileName;

            if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $dest_path)) {
                updateUserProfilePhoto($db, $userID, $newFileName);
                $_SESSION['profilePhoto'] = $newFileName;
            }
        }

        if (isset($_POST['specialty']) || isset($_POST['certifications'])) {
            $specialty = trim($_POST['specialty'] ?? '');
            $certifications = trim($_POST['certifications'] ?? '');
            updateTrainerData($db, $userID, $specialty, $certifications);
        }

        $_SESSION['messages'][] = ['type' => 'success', 'content' => 'Profile updated successfully!'];

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['messages'][] = ['type' => 'error', 'content' => 'This username is already in use.'];
        } else {
            $_SESSION['messages'][] = ['type' => 'error', 'content' => 'Database error occurred.'];
        }
    }
}

header('Location: ../profile.php');
exit;