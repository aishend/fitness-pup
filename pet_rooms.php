<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'templates/common.php';
require_once 'templates/scheduler_component.php';
require_once 'database/connection.php';
require_once 'database/pet_rooms.php';
require_once 'database/pets.php';

$dbh = getDatabaseConnection();
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;

$current_date_string = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$week_data = getWeekDateRange($current_date_string);

$rooms = getPetRooms($dbh);
$my_pets = ($userID) ? getPetsByOwner($dbh, $userID) : [];
$trainers = getRoomTrainers($dbh);

$filter_room = (isset($_GET['pet_roomID']) && $_GET['pet_roomID'] !== '') ? (int)$_GET['pet_roomID'] : '';

$filter_trainer = !empty($_GET['trainerID']) ? (int)$_GET['trainerID'] : '';

$filters_config = [];

$room_options = [['value' => '', 'label' => 'All Rooms']];
foreach ($rooms as $room) {
    $room_options[] = ['value' => $room['pet_roomID'], 'label' => $room['name']];
}
$filters_config[] = [
    'name' => 'pet_roomID',
    'label' => 'Select Room',
    'options' => $room_options,
    'selected' => $filter_room,
    'onchange' => false,
    'class' => 'room-filter-group'
];

$trainer_options = [['value' => '', 'label' => 'All Trainers']];
foreach ($trainers as $t) {
    $trainer_options[] = ['value' => $t['trainerID'], 'label' => $t['name']];
}
$filters_config[] = [
    'name' => 'trainerID',
    'label' => 'Select Trainer',
    'options' => $trainer_options,
    'selected' => $filter_trainer,
    'onchange' => false,
    'class' => 'room-filter-group'
];

$can_manage = in_array($user_role, ['admin', 'pet-trainer']);
$admin_btn  = $can_manage ? '<button type="button" class="btn btn-primary btn-create" onclick="openPetSessionModal(\'create\')">+ Create New Session</button>' : '';

drawHeader('schedule.css');
?>

<section class="schedule-hero">
    <h1>Pet Care Sessions</h1>
    <p>Filter by room or trainer and secure a spot for your companion. Your furry friend will be in good hands while you focus on your workout.</p>
</section>

<main class="schedule-wrapper">
    <?php drawSessionMessages(); ?>

    <?php
        render_filters(
            'pet_rooms.php',
            ['date' => $current_date_string],
            $filters_config,
            true,
            $admin_btn,
            'pet-filter-form'
        );
    ?>

    <?php
        render_week_navigation(
            'pet_rooms.php',
            $week_data['prev_date'],
            $week_data['next_date'],
            $week_data['display'],
            $week_data['week_number']
        );
    ?>

    <div id="pet-schedule-grid" data-current-date="<?= htmlspecialchars($current_date_string) ?>">
        <p class="no-classes-msg" style="padding:2rem;text-align:center;">Loading...</p>
    </div>

</main>

<section class="schedule-cta">
    <h2>Give Your Pet the Best Care!</h2>
    <p>Our dedicated pet care sessions ensure your companion is happy and safe. Book a spot today and enjoy your workout worry-free!</p>
    <div>
        <?php if ($user_role === 'admin'): ?>
            <button type="button" class="btn btn-primary" onclick="openPetSessionModal('create')">Create Session</button>
            <a href="admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        <?php else: ?>
            <a href="profile.php" class="btn btn-primary">Register Your Pet</a>
            <a href="about.php" class="btn btn-primary">Meet Our Trainers</a>
        <?php endif; ?>
    </div>
</section>

<?php if ($can_manage): ?>
<div id="petSessionModal" class="modal-overlay" onclick="handleOverlayClick(event, 'petSessionModal')">
    <div class="modal-box">
        <div class="modal-header">
            <h2 id="pet-session-modal-title">Pet Session</h2>
            <button type="button" class="btn-text-only" style="font-size: 1.5rem; text-decoration: none; color: var(--color-dark);" onclick="closePetSessionModal()">&times;</button>
        </div>
        <form method="POST" action="actions/admin/action_pet_session.php">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="action" id="modal-action">
            <input type="hidden" name="sessionID" id="modal-session-id">

            <div class="modal-body">
                <div class="input-group">
                    <label>Title</label>
                    <input type="text" name="title" id="modal-session-title" required placeholder="e.g. Morning Playtime">
                </div>
                <div class="input-group">
                    <label>Room</label>
                    <select name="pet_roomID" id="modal-session-room" required>
                        <?php foreach ($rooms as $r): ?>
                            <option value="<?= $r['pet_roomID'] ?>"><?= htmlspecialchars($r['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label>Trainer</label>
                    <?php if ($user_role === 'pet-trainer'): ?>
                        <input type="hidden" name="trainerID" id="modal-session-trainer" value="<?= $userID ?>">
                        <p><?= htmlspecialchars($_SESSION['name'] ?? '') ?></p>
                    <?php else: ?>
                        <select name="trainerID" id="modal-session-trainer" required>
                            <?php foreach ($trainers as $t): ?>
                                <option value="<?= $t['trainerID'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
                <div class="input-group">
                    <label>Capacity</label>
                    <input type="number" name="capacity" id="modal-session-capacity" min="1" required>
                </div>
                <div class="input-group">
                    <label>Date</label>
                    <input type="date" name="date" id="modal-session-date" required>
                </div>
                <div class="input-group">
                    <label>Start Time</label>
                    <input type="time" name="start_time" id="modal-session-start" required>
                </div>
                <div class="input-group">
                    <label>End Time</label>
                    <input type="time" name="end_time" id="modal-session-end" required>
                </div>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" onclick="closePetSessionModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="deletePetSessionModal" class="modal-overlay" onclick="handleOverlayClick(event, 'deletePetSessionModal')">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Confirm Deletion</h2>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete <strong id="delete-session-title"></strong>?</p>
            <p class="modal-sub">This action will cancel all pet enrollments for this session.</p>
        </div>
        <div class="modal-actions">
            <button type="button" id="confirm-delete-session-btn" class="btn btn-danger">Yes, Delete</button>
            <button type="button" class="btn btn-secondary" onclick="closeDeletePetSessionModal()">Cancel</button>
        </div>
        <form id="delete-session-form" method="POST" action="actions/admin/action_pet_session.php" style="display: none;">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="sessionID" id="delete-session-id">
        </form>
    </div>
</div>
<?php endif; // can_manage ?>

<?php if ($user_role === 'member'): ?>
<div id="petBookingModal" class="modal-overlay" data-pets="<?= htmlspecialchars(json_encode(array_values($my_pets)), ENT_QUOTES) ?>">
    <div class="modal-box">
        <div class="modal-header">
            <h2>🐾 Book a Pet Spot</h2>
        </div>
        <form method="POST" action="actions/action_pet.php" id="petBookingForm">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="action" value="manage_pets">
            <input type="hidden" name="sessionID" id="modal-session-id-book">
            <div class="modal-body">
                <p id="modal-spots-info"></p>
                <div class="modal-pets" id="modal-pets-list"></div>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn btn-primary" id="modal-confirm-btn">Confirm Booking</button>
                <button type="button" class="btn btn-outline" onclick="closePetBookingModal()">Maybe Later</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script src="js/manage_pet_sessions.js"></script>

<?php drawFooter(); ?>