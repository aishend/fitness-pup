<?php
session_start();

// Whitelist the redirect destination to prevent open redirect
$allowed_redirects = ['classes.php', 'profile.php'];
$redirect = $_POST['redirect'] ?? 'classes.php';
if (!in_array($redirect, $allowed_redirects, true)) {
    $redirect = 'classes.php';
}

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    header('Location: ../../' . $redirect);
    exit;
}

require_once '../../database/connection.php';
require_once '../../database/classes.php';
require_once '../../api/utils.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../' . $redirect);
    exit;
}

if (!isset($_SESSION['userID']) || !in_array($_SESSION['role'], ['admin', 'trainer'])) {
    header('Location: ../../login.php');
    exit;
}

$dbh    = getDatabaseConnection();
$action = $_POST['action'] ?? '';

// Verify the logged-in trainer owns the class (trainers cannot touch other trainers' classes)
function assertTrainerOwnsClass(PDO $dbh, int $classID): void {
    if ($_SESSION['role'] !== 'trainer') {
        return; // admins bypass this check
    }
    $myTrainerID    = getTrainerIDByUserID($dbh, (int)$_SESSION['userID']);
    $ownerTrainerID = getClassOwnerTrainerID($dbh, $classID);
    if (!$myTrainerID || $ownerTrainerID !== $myTrainerID) {
        $_SESSION['error'] = 'You are not authorised to modify this class.';
        header('Location: ../../profile.php');
        exit;
    }
}

try {
    if ($action === 'create' || $action === 'edit') {
        $title      = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
        $trainerID  = filter_input(INPUT_POST, 'trainerID', FILTER_VALIDATE_INT);
        $type       = trim(filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS));
        $capacity   = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);
        $date       = $_POST['date'] ?? '';
        $start_time = $_POST['start_time'] ?? '';
        $end_time   = $_POST['end_time'] ?? '';

        if (!$title || !$trainerID || !$type || !$capacity || !$date || !$start_time || !$end_time) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: ../../' . $redirect);
            exit;
        }

        if (strtotime($start_time) >= strtotime($end_time)) {
            $_SESSION['error'] = 'Start time must be before end time.';
            header('Location: ../../' . $redirect);
            exit;
        }

        // Trainers can only assign classes to themselves
        if ($_SESSION['role'] === 'trainer') {
            $myTrainerID = getTrainerIDByUserID($dbh, (int)$_SESSION['userID']);
            if (!$myTrainerID || (int)$trainerID !== $myTrainerID) {
                $_SESSION['error'] = 'You can only create or edit classes assigned to yourself.';
                header('Location: ../../profile.php');
                exit;
            }
        }

        $class_image = null;
        if (isset($_FILES['class_image']) && $_FILES['class_image']['error'] === UPLOAD_ERR_OK) {
            $class_image = handlePhotoUpload('class_image', '../../img/classes/', 'class_', (int)$_SESSION['userID']);
        }

        if ($action === 'create') {
            createClass($dbh, $title, $trainerID, $type, $capacity, $date, $start_time, $end_time, $class_image);
            $_SESSION['success'] = 'Class created successfully.';
        } else {
            $classID = filter_input(INPUT_POST, 'classID', FILTER_VALIDATE_INT);
            if (!$classID) {
                $_SESSION['error'] = 'Invalid class ID.';
                header('Location: ../../' . $redirect);
                exit;
            }
            assertTrainerOwnsClass($dbh, $classID);
            updateClass($dbh, $classID, $title, $trainerID, $type, $capacity, $date, $start_time, $end_time, $class_image);
            $_SESSION['success'] = 'Class updated successfully.';
        }

    } elseif ($action === 'remove') {
        $classID = filter_input(INPUT_POST, 'classID', FILTER_VALIDATE_INT);
        if (!$classID) {
            $_SESSION['error'] = 'Invalid class ID.';
            header('Location: ../../' . $redirect);
            exit;
        }
        assertTrainerOwnsClass($dbh, $classID);
        removeClassByAdmin($dbh, $classID);
        $_SESSION['success'] = 'Class removed.';
    }

} catch (Exception $e) {
    $_SESSION['error'] = 'An error occurred while processing your request.';
}

header('Location: ../../' . $redirect);
exit;
