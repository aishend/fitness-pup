<?php
session_start();

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    header('Location: ../../pet_rooms.php');
    exit;
}

require_once '../../database/connection.php';
require_once '../../database/pet_rooms.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pet_rooms.php');
    exit;
}

if (!isset($_SESSION['userID']) || !in_array($_SESSION['role'], ['admin', 'pet-trainer'])) {
    header('Location: ../../login.php');
    exit;
}

$action = $_POST['action'] ?? '';

// Verify the logged-in pet-trainer owns the session (pet-trainers cannot touch other trainers' sessions)
function assertPetTrainerOwnsSession(PDO $dbh, int $sessionID): void {
    if ($_SESSION['role'] !== 'pet-trainer') {
        return; // admins bypass this check
    }
    $ownerTrainerID = getPetSessionOwnerTrainerID($dbh, $sessionID);
    if ($ownerTrainerID === null || $ownerTrainerID !== (int)$_SESSION['userID']) {
        $_SESSION['error'] = 'You are not authorised to modify this session.';
        header('Location: ../../pet_rooms.php');
        exit;
    }
}

try {
    $dbh = getDatabaseConnection();

    if ($action === 'create' || $action === 'edit') {
        $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
        $pet_roomID = filter_input(INPUT_POST, 'pet_roomID', FILTER_VALIDATE_INT);
        $trainerID = filter_input(INPUT_POST, 'trainerID', FILTER_VALIDATE_INT);
        $capacity = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);
        $date = $_POST['date'] ?? '';
        $start_time = $_POST['start_time'] ?? '';
        $end_time = $_POST['end_time'] ?? '';

        if (empty($title) || !$pet_roomID || !$trainerID || !$capacity || empty($date) || empty($start_time) || empty($end_time)) {
            $_SESSION['error'] = "Todos os campos são obrigatórios e os IDs devem ser válidos.";
            header('Location: ../../pet_rooms.php');
            exit;
        }

        if (strtotime($start_time) >= strtotime($end_time)) {
            $_SESSION['error'] = "A hora de início deve ser anterior à hora de fim.";
            header('Location: ../../pet_rooms.php');
            exit;
        }

        // Pet-trainers can only assign sessions to themselves
        if ($_SESSION['role'] === 'pet-trainer' && (int)$trainerID !== (int)$_SESSION['userID']) {
            $_SESSION['error'] = 'You can only create or edit sessions assigned to yourself.';
            header('Location: ../../pet_rooms.php');
            exit;
        }

        if ($action === 'create') {
            createPetSession($dbh, $title, $pet_roomID, $trainerID, $capacity, $date, $start_time, $end_time);
            $_SESSION['success'] = "Sessão criada com sucesso!";
        } else {
            $sessionID = filter_input(INPUT_POST, 'sessionID', FILTER_VALIDATE_INT);
            if (!$sessionID) {
                $_SESSION['error'] = "ID de sessão inválido.";
                header('Location: ../../pet_rooms.php');
                exit;
            }
            assertPetTrainerOwnsSession($dbh, $sessionID);
            updatePetSession($dbh, $sessionID, $title, $pet_roomID, $trainerID, $capacity, $date, $start_time, $end_time);
            $_SESSION['success'] = "Sessão atualizada com sucesso!";
        }

    } elseif ($action === 'delete') {
        $sessionID = filter_input(INPUT_POST, 'sessionID', FILTER_VALIDATE_INT);
        if (!$sessionID) {
            $_SESSION['error'] = "ID de sessão inválido.";
            header('Location: ../../pet_rooms.php');
            exit;
        }
        assertPetTrainerOwnsSession($dbh, $sessionID);

        $dbh->beginTransaction();
        deletePetSession($dbh, $sessionID);
        $dbh->commit();

        $_SESSION['success'] = "Sessão eliminada com sucesso!";
    }

} catch (Exception $e) {
    if (isset($dbh) && $dbh->inTransaction()) {
        $dbh->rollBack();
    }
    $_SESSION['error'] = "Ocorreu um erro ao processar o pedido.";
}

header('Location: ../../pet_rooms.php');
exit;
?>