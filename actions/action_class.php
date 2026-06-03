<?php
session_start();

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    header('Location: ../classes.php');
    exit;
}

require_once '../database/connection.php';
require_once '../database/classes.php';
require_once '../database/pets.php';
require_once '../database/users.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../classes.php');
    exit;
}

if (!isset($_SESSION['userID'])) {
    header('Location: ../login.php');
    exit;
}

$dbh = getDatabaseConnection();
$userID = $_SESSION['userID'];
$user_role = $_SESSION['role'];

$classID = isset($_POST['classID']) ? (int)$_POST['classID'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';


if ($classID > 0) {
    try {
        if ($action === 'enroll' && $user_role === 'member') {
            enrollInClass($dbh, $userID, $classID);

            $userData = getUserProfileById($dbh, $userID);

            if (!$userData['skip_pet_prompt']) {
                $pets = getMemberPets($dbh, $userID);
                $_SESSION['show_pet_prompt'] = true;
                $_SESSION['pet_prompt_pets'] = $pets;
            }

        } elseif ($action === 'cancel' && $user_role === 'member') {
            cancelClassEnrollment($dbh, $userID, $classID);

        } elseif ($action === 'remove' && $user_role === 'admin') {
            removeClassByAdmin($dbh, $classID);

        } elseif ($action === 'skip_pet_prompt' && $user_role === 'member') {
            setSkipPetPrompt($dbh, $userID);
        }

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "An error occurred while processing your request.";
    }
}

header("Location: ../classes.php");
exit;
?>
