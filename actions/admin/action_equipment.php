<?php
session_start();

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    header('Location: ../../equipment.php');
    exit;
}

require_once '../../database/connection.php';
require_once '../../database/equipment.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../login.php');
    exit;
}

$dbh = getDatabaseConnection();
$action = $_POST['action'] ?? '';

try {
    if ($action === 'delete') {
        $id = (int)$_POST['equipmentID'];
        deleteEquipment($dbh, $id);
        $_SESSION['messages'][] = ['type' => 'success', 'content' => 'Equipment deleted successfully!'];

    } elseif ($action === 'create' || $action === 'edit') {
        $name = $_POST['name'];
        $categoryID = (int)$_POST['categoryID'];
        $status = $_POST['status'];
        $description = $_POST['description'];
        $equipmentID = isset($_POST['equipmentID']) ? (int)$_POST['equipmentID'] : null;

        $imageName = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], '../../img/equipment/' . $imageName);
        }

        if ($action === 'create') {
            addEquipmentFull($dbh, $name, $description, $categoryID, $status, $imageName);
            $_SESSION['messages'][] = ['type' => 'success', 'content' => 'Equipment added successfully!'];
        } else {
            updateEquipment($dbh, $equipmentID, $name, $description, $categoryID, $status, $imageName);
            $_SESSION['messages'][] = ['type' => 'success', 'content' => 'Equipment updated successfully!'];
        }
    }
} catch (PDOException $e) {
    $_SESSION['messages'][] = ['type' => 'error', 'content' => 'Database error. Make sure the inputs are valid.'];
}

header('Location: ../../equipment.php');
exit;