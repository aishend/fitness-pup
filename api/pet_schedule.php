<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/connection.php';
require_once '../database/pet_rooms.php';
require_once '../templates/scheduler_component.php';

header('Content-Type: application/json; charset=utf-8');

$user_role = $_SESSION['role'] ?? 'guest';
$userID    = $_SESSION['userID'] ?? null;

// Validate date
$date_raw = $_GET['date'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_raw) || strtotime($date_raw) === false) {
    $date_raw = date('Y-m-d');
}

$filter_room    = (isset($_GET['pet_roomID']) && $_GET['pet_roomID'] !== '') ? (int)$_GET['pet_roomID'] : '';
$filter_trainer = (isset($_GET['trainerID'])  && $_GET['trainerID']  !== '') ? (int)$_GET['trainerID']  : '';

$week_data = getWeekDateRange($date_raw);
$dbh       = getDatabaseConnection();

$db_sessions = getPetScheduleData(
    $dbh, $userID,
    $filter_room, $filter_trainer,
    $week_data['start']->format('Y-m-d 00:00:00'),
    $week_data['end_sql']
);

$session_ids       = array_column($db_sessions, 'reservationID');
$enrolled_pets_map = $userID ? getEnrolledPetsBySession($dbh, $userID, $session_ids) : [];

$formatted = [];
foreach ($db_sessions as $res) {
    $start = new DateTime($res['start_time']);
    $end   = new DateTime($res['end_time']);
    $formatted[] = [
        'reservationID' => $res['reservationID'],
        'day'           => $start->format('l'),
        'time'          => $start->format('H:i') . ' - ' . $end->format('H:i'),
        'full_start'    => $res['start_time'],
        'full_end'      => $res['end_time'],
        'date'          => $start->format('Y-m-d'),
        'start_time'    => $start->format('H:i'),
        'end_time'      => $end->format('H:i'),
        'title'         => $res['title'],
        'room_name'     => $res['roomName'],
        'pet_roomID'    => $res['pet_roomID'] ?? '',
        'trainer'       => $res['trainer'],
        'trainerID'     => $res['trainerID'] ?? '',
        'booked'        => $res['booked'],
        'capacity'      => $res['capacity'],
        'is_enrolled'   => $res['is_enrolled'] > 0,
    ];
}

$my_petTrainerID = ($user_role === 'pet-trainer') ? $userID : null;

ob_start();
render_pet_schedule($formatted, $user_role, [], $enrolled_pets_map, $my_petTrainerID);
$grid_html = ob_get_clean();

echo json_encode([
    'grid'        => $grid_html,
    'weekDisplay' => $week_data['display'],
    'weekNumber'  => $week_data['week_number'],
    'prevDate'    => $week_data['prev_date'],
    'nextDate'    => $week_data['next_date'],
], JSON_UNESCAPED_UNICODE);
