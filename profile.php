<?php
session_start();

require_once 'database/connection.php';
require_once 'database/users.php';
require_once 'database/details.php';
require_once 'templates/common.php';
require_once 'database/pets.php';

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

$isOwner   = !isset($_GET['id']) || ($_GET['id'] == $_SESSION['userID']);
$profileID = $isOwner ? $_SESSION['userID'] : intval($_GET['id']);

$db = getDatabaseConnection();

$user = getUserProfileById($db, $profileID);
if (!$user) {
    die("Utilizador não encontrado.");
}

if ($user['role'] === 'admin') {
    header('Location: admin_dashboard.php');
    exit;
}

$trainerData = null;
$memberStats = null;
$frequentedClasses = [];
$sessions = [];

if ($user['role'] === 'trainer' || $user['role'] === 'pet-trainer') {
    require_once 'database/reviews.php';
    $trainerData = getTrainerData($db, $user['userID']);
    $trainerReviews = ($user['role'] === 'trainer' && !empty($trainerData['trainerID']))
        ? getReviewsForTrainer($db, $trainerData['trainerID'])
        : [];
} else if ($user['role'] === 'member') {
    require_once 'database/reviews.php';
    $memberStats = getMemberStatsData($db, $user['userID']);
    $memberPets = getMemberPets($db, $user['userID']);
    $frequentedClasses = getFrequentedClasses($db, $user['userID']);
    $completedClasses = getCompletedClassesWithReviews($db, $user['userID']);
}

if ($user['role'] === 'trainer') {
    $sessionType = 'class';
    $sessions = getUpcomingTrainerClasses($db, $user['name'], $trainerData['trainerID'] ?? 0);
} elseif ($user['role'] === 'pet-trainer') {
    $sessionType = 'pet_session';
    $sessions = getUpcomingPetTrainerSessions($db, $user['name'], $user['userID']);
} else {
    $sessionType = 'class';
    $sessions = getUpcomingMemberClasses($db, $user['userID']);
}

$userBadges = getUserBadges($db, $user['userID']);

drawHeader('profile.css');
?>

<main class="profile-container">
    <?php drawSessionMessages(); ?>
    <section class="profile-hero">
    <div class="profile-avatar">
        <?php
            $dbPhoto = $user['profilePhoto'] ?? '';
            $photoPath = "./img/users/" . htmlspecialchars($dbPhoto);

            if (empty($dbPhoto) || !file_exists($photoPath) || !is_file($photoPath)) {
                $photoPath = "./img/users/user-avatar.png";
            }
        ?>
        <img src="<?php echo $photoPath; ?>" alt="User Avatar">
    </div>
    <div class="profile-info">
        <h1><?php echo htmlspecialchars($user['name']); ?></h1>
        <p class="username">@<?php echo htmlspecialchars($user['username']); ?></p>
        <p class="bio"><?php echo htmlspecialchars($user['bio'] ?? 'No bio available.'); ?></p>

        <?php if (($user['role'] === 'trainer' || $user['role'] === 'pet-trainer') && $trainerData): ?>
            <div class="trainer-badges" style="margin-top: 15px;">
                <?php if (!empty($trainerData['specialty'])): ?>
                    <p class="trainer-spec"><strong>🎯 Specialty:</strong> <?php echo htmlspecialchars($trainerData['specialty']); ?></p>
                <?php endif; ?>
                <?php if (!empty($trainerData['certifications'])): ?>
                    <p class="trainer-certs"><strong>📜 Certifications:</strong> <?php echo htmlspecialchars($trainerData['certifications']); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($isOwner): ?>
            <button type="button" class="btn btn-primary" style="margin-top: 15px;" onclick="openEditProfileModal()">Edit Profile</button>
        <?php endif; ?>
    </div>
    </section>

    <section class="upcoming-sessions">
        <h2>
        <?php
            if ($user['role'] === 'trainer' || $user['role'] === 'pet-trainer') {
                echo $isOwner ? "My Teaching Schedule" : "Classes Taught by this Trainer";
            } else {
                echo "Upcoming Sessions";
            }
        ?>
        </h2>
        <div class="sessions-container" data-paginator>
            <?php if (empty($sessions)): ?>
                <p>No upcoming sessions scheduled.</p>
            <?php else: ?>
                <?php foreach ($sessions as $index => $session):
                    $dateObj = new DateTime($session['date'] ?? $session['start_time']);
                    $isTrainerOwner = $isOwner && $user['role'] === 'trainer';
                ?>
                <div class="session-card">
                    <div class="session-date-box <?php echo ($index % 2 !== 0) ? 'secondary' : ''; ?>">
                        <span class="day"><?php echo $dateObj->format('d'); ?></span>
                        <span class="month"><?php echo strtoupper($dateObj->format('M')); ?></span>
                    </div>
                    <div class="session-main">
                        <div class="session-info">
                            <h3><?php echo htmlspecialchars($session['name'] ?? $session['title']); ?></h3>
                            <p>
                                <i class="icon">🕒</i>
                                <?php echo $dateObj->format('h:i A'); ?> •
                                <?php echo htmlspecialchars($session['duration']); ?> min
                            </p>
                            <?php if ($sessionType === 'pet_session'): ?>
                                <p><i class="icon">📍</i> <?php echo htmlspecialchars($session['roomName']); ?></p>
                            <?php else: ?>
                                <p><i class="icon">👤</i> Trainer: <?php echo htmlspecialchars($session['trainerName']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="session-actions">
                            <?php if ($user['role'] !== 'member'): ?>
                                <?php if ($isOwner): ?>
                                    <a href="session_details.php?<?= $user['role'] === 'pet-trainer' ? 'sessionID=' . $session['sessionID'] : 'classID=' . $session['classID'] ?>"
                                        class="btn-details" style="text-decoration:none;">Details</a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($isTrainerOwner): ?>
                                <?php
                                    $editDate      = (new DateTime($session['date']))->format('Y-m-d');
                                    $editStart     = (new DateTime($session['date']))->format('H:i');
                                    $editEnd       = !empty($session['end_time']) ? (new DateTime($session['end_time']))->format('H:i') : '';
                                    $editType      = htmlspecialchars($session['type'] ?? '', ENT_QUOTES);
                                    $editCapacity  = (int)($session['capacity'] ?? 0);
                                    $editName      = htmlspecialchars($session['name'] ?? '', ENT_QUOTES);
                                    $editClassID   = (int)$session['classID'];
                                    $editTrainerID = (int)($trainerData['trainerID'] ?? 0);
                                    $editImage     = htmlspecialchars($session['class_image'] ?? '', ENT_QUOTES);
                                ?>
                                <button class="btn-details"
                                    onclick="openProfileClassModal(
                                        <?= $editClassID ?>,
                                        '<?= $editName ?>',
                                        <?= $editTrainerID ?>,
                                        '<?= $editType ?>',
                                        <?= $editCapacity ?>,
                                        '<?= $editDate ?>',
                                        '<?= $editStart ?>',
                                        '<?= $editEnd ?>',
                                        '<?= $editImage ?>'
                                    )">Edit</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($user['role'] === 'trainer' && !empty($trainerReviews)): ?>
    <section class="trainer-reviews-section">
        <h2>Member Reviews</h2>
        <div class="trainer-reviews-container" data-paginator>
            <?php foreach ($trainerReviews as $review): ?>
            <div class="trainer-review-card">
                <div class="trainer-review-header">
                    <span class="review-stars">
                        <?= str_repeat('★', (int)$review['rating']) ?><?= str_repeat('☆', 5 - (int)$review['rating']) ?>
                    </span>
                    <span class="trainer-review-meta">
                        <?= htmlspecialchars($review['class_name']) ?> &bull;
                        <?= (new DateTime($review['start_time']))->format('d M Y') ?>
                    </span>
                </div>
                <?php if (!empty($review['comment'])): ?>
                    <p class="trainer-review-comment">"<?= htmlspecialchars($review['comment']) ?>"</p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($user['role'] === 'member'): ?>
    <section class="stats-grid">
        <div class="stat-card">
            <h3><a href="workout_history.php">Workouts</a></h3>
            <p class="stat-value"><?php echo intval($memberStats['workout_count'] ?? 0); ?></p>
        </div>
        <div class="stat-card">
            <h3>Weekly Streak</h3>
            <p class="stat-value"><?php echo intval($memberStats['weekly_streak'] ?? 0); ?></p>
        </div>
    </section>

    <section class="pets-section">
        <div class="pets-header">
            <h2>My Pets</h2>
            <?php if ($isOwner): ?>
                <a href="register_pet.php" class="btn btn-dark">+ Add Pet</a>
            <?php endif; ?>
        </div>
        <div class="pets-grid">
            <?php if (empty($memberPets)): ?>
                <p>No pets registered yet.</p>
            <?php else: ?>
                <?php foreach ($memberPets as $pet): ?>
                <a href="pet_profile.php?id=<?= $pet['petID'] ?>" class="pet-card-link">
                <div class="pet-card">
                    <div class="pet-photo">
                        <img src="./img/pets/<?= htmlspecialchars($pet['photo'] ?? 'default_pet.png') ?>"
                            alt="<?= htmlspecialchars($pet['name']) ?>"
                            onerror="this.src='./img/pets/default_pet.png'">
                    </div>
                    <div class="pet-info">
                        <h4><?= htmlspecialchars($pet['name']) ?></h4>
                        <p class="pet-breed"><?= htmlspecialchars($pet['breed']) ?></p>
                        <p class="pet-age"><?= $pet['age'] ?> years old</p>
                        <span class="vacc-badge <?= $pet['vaccinated'] ? 'vacc-yes' : 'vacc-no' ?>">
                            <?= $pet['vaccinated'] ? '✓ Vaccinated' : '✗ Not Vaccinated' ?>
                        </span>
                    </div>
                </div>
                </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="training-focus">
        <h2>Most Frequented Classes</h2>
        <div class="focus-container">
            <?php if (empty($frequentedClasses)): ?>
                <p style="grid-column: 1/-1; color: #666;">You haven't enrolled in any classes yet.</p>
            <?php else:
                $maxFrequency = $frequentedClasses[0]['frequency'] ?: 1;
                foreach ($frequentedClasses as $index => $classItem):
                    $percentage = round(($classItem['frequency'] / $maxFrequency) * 100);
                    $barClass = ($index % 2 !== 0) ? 'secondary' : '';
                ?>
                <div class="focus-item">
                    <div class="focus-label">
                        <span><?php echo htmlspecialchars($classItem['class_name']); ?></span>
                        <span><?php echo intval($classItem['frequency']); ?> <?php echo $classItem['frequency'] == 1 ? 'time' : 'times'; ?></span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress <?php echo $barClass; ?>" style="width: <?php echo $percentage; ?>%;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="class-history-section">
        <h2>Class History</h2>
        <div class="class-history-container" data-paginator>
            <?php if (empty($completedClasses)): ?>
                <p style="color:#666;">No completed classes yet.</p>
            <?php else: ?>
                <?php foreach ($completedClasses as $entry):
                    $dateObj = new DateTime($entry['start_time']);
                ?>
                <div class="class-history-card">
                    <div class="history-date-box">
                        <span class="day"><?= $dateObj->format('d') ?></span>
                        <span class="month"><?= strtoupper($dateObj->format('M')) ?></span>
                    </div>
                    <div class="history-main">
                        <div class="history-info">
                            <h3><?= htmlspecialchars($entry['class_name']) ?></h3>
                            <p><?= htmlspecialchars($entry['type']) ?> &bull; <?= htmlspecialchars($entry['trainerName']) ?></p>
                        </div>
                        <div class="history-review">
                            <?php if ($entry['reviewID']): ?>
                                <div class="review-display">
                                    <span class="review-stars">
                                        <?= str_repeat('★', (int)$entry['rating']) ?><?= str_repeat('☆', 5 - (int)$entry['rating']) ?>
                                    </span>
                                    <?php if (!empty($entry['comment'])): ?>
                                        <p class="review-comment">"<?= htmlspecialchars($entry['comment']) ?>"</p>
                                    <?php endif; ?>
                                </div>
                                <?php if ($isOwner): ?>
                                    <form method="POST" action="actions/action_review.php" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="action" value="delete_review">
                                        <input type="hidden" name="reviewID" value="<?= (int)$entry['reviewID'] ?>">
                                        <button type="submit" class="btn-delete-review">Delete</button>
                                    </form>
                                <?php endif; ?>
                            <?php elseif ($isOwner): ?>
                                <button class="btn btn-primary btn-review"
                                        onclick="openReviewModal(<?= (int)$entry['enrollmentID'] ?>)">
                                    Leave a Review
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="badges-section">
        <h2>My Achievements</h2>
        <div class="badges-grid">
            <?php if (empty($userBadges)): ?>
                <p>No achievements earned yet. Keep training!</p>
            <?php else: ?>
                <?php foreach ($userBadges as $badge): ?>
                <div class="badge-card-earned">
                    <div class="badge-icon">
                        <img src="./img/<?php echo htmlspecialchars($badge['image_path']); ?>" alt="<?php echo htmlspecialchars($badge['title']); ?>">
                    </div>
                    <div class="badge-info">
                        <h4><?php echo htmlspecialchars($badge['title']); ?></h4>
                        <p><?php echo htmlspecialchars($badge['description']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php if ($isOwner): ?>
<div id="editProfileModal" class="modal-overlay" onclick="handleProfileOverlayClick(event)">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Edit Profile</h2>
        </div>
        <form method="POST" action="actions/action_edit_profile.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <input type="hidden" name="action" value="update_profile">

            <div class="modal-body">
                <div class="input-group">
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="input-group">
                    <label>Bio</label>
                    <textarea name="bio" rows="3"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                </div>
                <div class="input-group">
                    <label>Profile Photo</label>
                    <input type="file" name="profilePhoto" accept="image/*">
                </div>

                <?php if ($user['role'] === 'trainer' || $user['role'] === 'pet-trainer'): ?>
                    <div class="input-group">
                        <label>Specialty</label>
                        <input type="text" name="specialty" value="<?php echo htmlspecialchars($trainerData['specialty'] ?? ''); ?>">
                    </div>
                    <div class="input-group">
                        <label>Certifications</label>
                        <input type="text" name="certifications" value="<?php echo htmlspecialchars($trainerData['certifications'] ?? ''); ?>">
                    </div>
                <?php endif; ?>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditProfileModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="reviewModal" class="modal-overlay" onclick="handleReviewOverlayClick(event)">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Leave a Review</h2>
            <button type="button" class="btn-text-only" style="font-size: 1.5rem;" onclick="closeReviewModal()">&times;</button>
        </div>
        <form method="POST" action="actions/action_review.php">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="action" value="submit_review">
            <input type="hidden" name="enrollmentID" id="review-enrollment-id" value="">
            <div class="modal-body">
                <div class="input-group">
                    <label>Rating</label>
                    <div class="star-rating">
                        <input type="radio" id="star5" name="rating" value="5">
                        <label for="star5" title="5 - Excellent">&#9733;</label>
                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4" title="4 - Good">&#9733;</label>
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3" title="3 - Average">&#9733;</label>
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2" title="2 - Poor">&#9733;</label>
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1" title="1 - Very Poor">&#9733;</label>
                    </div>
                </div>
                <div class="input-group">
                    <label>Comment <span style="color:#888; font-weight:normal;">(optional, max 1000 chars)</span></label>
                    <textarea name="comment" id="review-comment" rows="4" maxlength="1000" placeholder="Share your experience..."></textarea>
                </div>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">Submit Review</button>
                <button type="button" class="btn btn-secondary" onclick="closeReviewModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="js/profile.js"></script>
<?php endif; ?>

<?php if ($isOwner && $user['role'] === 'trainer'): ?>
<div id="profileClassModal" class="modal-overlay" onclick="handleProfileClassOverlayClick(event)">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Edit Class</h2>
            <button type="button" class="btn-text-only" style="font-size:1.5rem;" onclick="closeProfileClassModal()">&times;</button>
        </div>
        <form method="POST" action="actions/admin/action_class.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="redirect" value="profile.php">
            <input type="hidden" name="classID" id="profile-modal-class-id">
            <input type="hidden" name="trainerID" value="<?= (int)($trainerData['trainerID'] ?? 0) ?>">

            <div class="modal-body">
                <div class="input-group">
                    <label>Title</label>
                    <input type="text" name="title" id="profile-modal-title" required placeholder="e.g. Yoga Flow">
                </div>
                <div class="input-group">
                    <label>Class Type</label>
                    <input type="text" name="type" id="profile-modal-type" required placeholder="e.g. yoga, cardio">
                </div>
                <div class="input-group">
                    <label>Capacity</label>
                    <input type="number" name="capacity" id="profile-modal-capacity" min="1" required>
                </div>
                <div class="input-group">
                    <label>Date</label>
                    <input type="date" name="date" id="profile-modal-date" required>
                </div>
                <div class="input-group">
                    <label>Start Time</label>
                    <input type="time" name="start_time" id="profile-modal-start" required>
                </div>
                <div class="input-group">
                    <label>End Time</label>
                    <input type="time" name="end_time" id="profile-modal-end" required>
                </div>
                <div class="input-group">
                    <label>Class Photo</label>
                    <div id="profile-modal-image-preview" style="display:none; margin-bottom:0.5rem;">
                        <img id="profile-modal-current-image" src="" alt="Current class photo" class="class-modal-preview-img">
                        <p style="font-size:0.8rem; color:#666; margin:0.3rem 0 0;">Upload a new photo to replace the current one.</p>
                    </div>
                    <input type="hidden" name="current_class_image" id="profile-modal-current-class-image">
                    <input type="file" name="class_image" accept="image/jpeg,image/png,image/webp">
                </div>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" onclick="closeProfileClassModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php endif; ?>

<?php drawFooter(); ?>