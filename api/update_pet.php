<?php
session_start();
header('Content-Type: application/json');
require_once '../database/connection.php';
require_once '../database/pets.php';
require_once 'utils.php';

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

if (!isset($_SESSION['userID'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

$petID      = (int)($_POST['petID'] ?? 0);
$name       = trim($_POST['name'] ?? '');
$breed      = trim($_POST['breed'] ?? '');
$age        = (int)($_POST['age'] ?? 0);
$vaccinated = isset($_POST['vaccinated']) ? 1 : 0;

if (!$petID || !$name || !$breed || $age < 0) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

$dbh = getDatabaseConnection();
$pet = getPetById($dbh, $petID);

if (!$pet || $pet['ownerID'] != $_SESSION['userID']) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

try {
    $newPhoto = handlePhotoUpload('petPhoto', '../img/pets/', 'pet_', $_SESSION['userID']);
    $photo    = $newPhoto ?: $pet['photo'];

    updatePet($dbh, $petID, $name, $breed, $age, $vaccinated, $photo);
    echo json_encode(['success' => true, 'message' => 'Pet profile updated!', 'photo' => $photo]);

} catch (InvalidArgumentException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
