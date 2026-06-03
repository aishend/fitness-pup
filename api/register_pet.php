<?php
session_start();
require_once '../database/connection.php';
require_once '../database/pets.php';
require_once '../database/users.php';
require_once 'utils.php';

header('Content-Type: application/json');

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'member') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

$userID      = $_SESSION['userID'];
$name        = trim($_POST['name'] ?? '');
$breed       = trim($_POST['breed'] ?? '');
$age         = intval($_POST['age'] ?? 0);
$vaccinated  = isset($_POST['vaccinated']) ? 1 : 0;
$from_prompt = ($_POST['from_prompt'] ?? '0') === '1';

if (!$name || !$breed || $age < 0) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

try {
    $newPhoto = handlePhotoUpload('petPhoto', '../img/pets/', 'pet_', $userID);
    $photo    = $newPhoto ?: 'default_pet.png';

    $dbh = getDatabaseConnection();
    registerPet($dbh, $userID, $name, $breed, $age, $vaccinated, $photo);
    clearSkipPetPrompt($dbh, $userID);

    $redirect = $from_prompt ? 'pet_rooms.php' : 'profile.php';
    echo json_encode(['success' => true, 'redirect' => $redirect]);

} catch (InvalidArgumentException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
