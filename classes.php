<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'templates/common.php';
require_once 'templates/scheduler_component.php';
require_once 'database/connection.php';
require_once 'database/classes.php';

$dbh = getDatabaseConnection();

$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;

$current_date_string = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$week_data = getWeekDateRange($current_date_string);

$filter_type = isset($_GET['type']) ? $_GET['type'] : '';
$filter_trainer = isset($_GET['trainerID']) ? $_GET['trainerID'] : '';

$db_classes = getScheduleData($dbh, $userID, $filter_type, $filter_trainer, $week_data['start']->format('Y-m-d 00:00:00'), $week_data['end_sql']);
$class_types = getClassTypesFromDB($dbh);
$trainers = getTrainersForClasses($dbh);

$type_options = [['value' => '', 'label' => 'All Types']];
foreach ($class_types as $type) {
    $type_options[] = ['value' => $type, 'label' => ucfirst($type)];
}

$trainer_options = [['value' => '', 'label' => 'All Trainers']];
foreach ($trainers as $trainer) {
    $trainer_options[] = ['value' => $trainer['trainerID'], 'label' => $trainer['name']];
}

$filters_config = [
    [
        'name' => 'type',
        'label' => 'Class Type',
        'options' => $type_options,
        'selected' => $filter_type,
        'onchange' => false
    ],
    [
        'name' => 'trainerID',
        'label' => 'Trainer',
        'options' => $trainer_options,
        'selected' => $filter_trainer,
        'onchange' => false
    ]
];

$can_manage = in_array($user_role, ['admin', 'trainer']);
$admin_btn  = $can_manage ? '<button type="button" onclick="openClassModal(\'create\')" class="btn btn-primary btn-create">+ Create New Class</button>' : '';

$my_trainerID = ($user_role === 'trainer' && $userID) ? getTrainerIDByUserID($dbh, $userID) : null;

$formatted_classes = [];
foreach ($db_classes as $class) {
    $start_date = new DateTime($class['start_time']);
    $end_date = new DateTime($class['end_time']);

    $formatted_classes[] = [
        'classID' => $class['classID'],
        'day' => $start_date->format('l'),
        'time' => $start_date->format('H:i') . ' - ' . $end_date->format('H:i'),
        'title' => $class['title'],
        'trainer' => $class['trainer'],
        'trainerID' => $class['trainerID'] ?? '',
        'trainerUserID' => $class['trainerUserID'] ?? '',
        'type' => $class['type'] ?? '',
        'date' => $start_date->format('Y-m-d'),
        'start_time' => $start_date->format('H:i'),
        'end_time' => $end_date->format('H:i'),
        'booked' => $class['booked'],
        'capacity' => $class['capacity'],
        'is_enrolled' => $class['is_enrolled'] > 0,
        'class_image' => $class['class_image'] ?? null
    ];
}

$show_pet_prompt = false;
$prompt_pets = [];

if (isset($_SESSION['show_pet_prompt'])) {
    $show_pet_prompt = true;
    $prompt_pets = $_SESSION['pet_prompt_pets'] ?? [];
    unset($_SESSION['show_pet_prompt']);
    unset($_SESSION['pet_prompt_pets']);
}

drawHeader('schedule.css');
?>

<section class="schedule-hero">
    <h1>Our Class Schedule</h1>
    <p>Browse, filter, and book your next workout session with our expert trainers.</p>
</section>

<main class="schedule-wrapper">

    <?php
        render_filters(
            'classes.php',
            ['date' => $current_date_string],
            $filters_config,
            true,
            $admin_btn
        );
    ?>

    <?php
        $extra_params = '&type=' . urlencode($filter_type) . '&trainerID=' . urlencode($filter_trainer);
        render_week_navigation(
            'classes.php',
            $week_data['prev_date'],
            $week_data['next_date'],
            $week_data['display'],
            $week_data['week_number'],
            $extra_params
        );
    ?>

    <?php render_schedule($formatted_classes, $user_role, $my_trainerID); ?>

</main>

<div id="classModal" class="modal-overlay" onclick="handleOverlayClick(event, 'classModal')">
    <div class="modal-box">
        <div class="modal-header">
            <h2 id="class-modal-title">Class</h2>
            <button type="button" class="btn-text-only" style="font-size: 1.5rem; text-decoration: none; color: var(--color-dark);" onclick="closeClassModal()">&times;</button>
        </div>
        <form method="POST" action="actions/admin/action_class.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="action" id="modal-action">
            <input type="hidden" name="classID" id="modal-class-id">

            <div class="modal-body">
                <div class="input-group">
                    <label>Title</label>
                    <input type="text" name="title" id="modal-title" required placeholder="e.g. Yoga Flow">
                </div>
                <div class="input-group">
                    <label>Trainer</label>
                    <?php if ($my_trainerID): ?>
                        <input type="hidden" name="trainerID" id="modal-trainer" value="<?= $my_trainerID ?>">
                        <p><?= htmlspecialchars($_SESSION['name'] ?? '') ?></p>
                    <?php else: ?>
                        <select name="trainerID" id="modal-trainer" required>
                            <?php foreach ($trainers as $t): ?>
                                <option value="<?= $t['trainerID'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
                <div class="input-group">
                    <label>Class Type</label>
                    <input type="text" name="type" id="modal-type" required placeholder="e.g. yoga, cardio">
                </div>
                <div class="input-group">
                    <label>Capacity</label>
                    <input type="number" name="capacity" id="modal-capacity" min="1" required>
                </div>
                <div class="input-group">
                    <label>Date</label>
                    <input type="date" name="date" id="modal-date" required>
                </div>
                <div class="input-group">
                    <label>Start Time</label>
                    <input type="time" name="start_time" id="modal-start-time" required>
                </div>
                <div class="input-group">
                    <label>End Time</label>
                    <input type="time" name="end_time" id="modal-end-time" required>
                </div>
                <div class="input-group">
                    <label>Class Photo</label>
                    <div id="modal-image-preview" style="display:none; margin-bottom:0.5rem;">
                        <img id="modal-current-image" src="" alt="Current class photo" class="class-modal-preview-img">
                        <p style="font-size:0.8rem; color:#666; margin:0.3rem 0 0;">Upload a new photo to replace the current one.</p>
                    </div>
                    <input type="hidden" name="current_class_image" id="modal-current-class-image">
                    <input type="file" name="class_image" accept="image/jpeg,image/png,image/webp">
                </div>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" onclick="closeClassModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="classDeleteModal" class="modal-overlay" onclick="handleOverlayClick(event, 'classDeleteModal')">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Confirm Deletion</h2>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete <strong id="delete-class-title"></strong>?</p>
            <p class="modal-sub">This action will cancel all member enrollments for this class.</p>
        </div>
        <div class="modal-actions">
            <button type="button" id="confirm-delete-class-btn" class="btn btn-danger">Yes, Delete</button>
            <button type="button" class="btn btn-secondary" onclick="closeDeleteClassModal()">Cancel</button>
        </div>
    </div>
</div>

<?php if ($show_pet_prompt): ?>
<div class="modal-overlay visible" id="petPromptModal">
    <div class="modal-box">
        <div class="modal-header">
            <h2>🐾 Bring Your Pup?</h2>
        </div>
        <?php if (!empty($prompt_pets)): ?>
            <div class="modal-body">
                <p>You're booked in! Want to bring your furry friend along while you train?</p>
                <div class="modal-pets">
                    <?php foreach ($prompt_pets as $pet): ?>
                        <div class="modal-pet-item">
                            <span class="pet-name">🐶 <?= htmlspecialchars($pet['name']) ?></span>
                            <span class="pet-breed"><?= htmlspecialchars($pet['breed']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-actions">
                <a href="pet_rooms.php" class="btn btn-primary">Book a Pet Spot</a>
                <button type="button" class="btn btn-outline" onclick="closePetPrompt()">Maybe Later</button>
            </div>
        <?php else: ?>
            <div class="modal-body">
                <p>You're booked in! Do you have a pet you'd like to bring along while you train?</p>
                <p class="modal-sub">Register your pet to book them a spot in our pet care rooms.</p>
            </div>
            <div class="modal-actions">
                <a href="register_pet.php?from=prompt" class="btn btn-primary">Register My Pet</a>
                <button type="button" class="btn btn-outline" onclick="closePetPrompt()">Maybe Later</button>
                <form method="POST" action="actions/action_class.php">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="action" value="skip_pet_prompt">
                    <input type="hidden" name="classID" value="0">
                    <button type="submit" class="btn-text-only">I don't have a pet — don't ask again</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<script src="js/manage_classes.js"></script>

<?php drawFooter(); ?>