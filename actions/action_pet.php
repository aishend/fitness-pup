<?php
session_start();

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    header('Location: ../pet_rooms.php');
    exit;
}

require_once '../database/connection.php';
require_once '../database/pet_rooms.php';
require_once '../database/pets.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['userID'])) {
    header('Location: ../pet_rooms.php');
    exit;
}

$dbh     = getDatabaseConnection();
$action  = isset($_POST['action']) ? $_POST['action'] : '';

try {
    if ($action === 'reserve') {
        $sessionID = (int)$_POST['reservationID'];
        $petID     = (int)$_POST['petID'];
        enrollPetInSession($dbh, $sessionID, $petID);

    } elseif ($action === 'cancel') {
        $sessionID = (int)$_POST['reservationID'];
        $petID     = (int)$_POST['petID'];
        cancelPetEnrollment($dbh, $sessionID, $petID);

    } elseif ($action === 'manage_pets') {
        $sessionID      = (int)$_POST['sessionID'];
        $selectedPetIDs = array_map('intval', $_POST['selected_pets'] ?? []);

        $userPetIDs = getOwnerPetIDs($dbh, $_SESSION['userID']);

        if (!empty($userPetIDs)) {
            $currentlyEnrolled = getUserEnrolledPetIDsForSession($dbh, $_SESSION['userID'], $sessionID, $userPetIDs);

            $selectedPetIDs = array_values(array_intersect($selectedPetIDs, $userPetIDs));
            $toCancel = array_diff($currentlyEnrolled, $selectedPetIDs);
            $toEnroll = array_diff($selectedPetIDs, $currentlyEnrolled);

            foreach ($toCancel as $petID) {
                cancelPetEnrollment($dbh, $sessionID, $petID);
            }
            foreach ($toEnroll as $petID) {
                enrollPetInSession($dbh, $sessionID, $petID);
            }
        }
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = "An error occurred while processing your request.";
}

header("Location: ../pet_rooms.php");
exit;
