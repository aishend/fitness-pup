<?php
session_start();
require_once 'templates/common.php';
require_once 'database/connection.php';
require_once 'database/pets.php';

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit;
}

$petID = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$petID) {
    header('Location: profile.php');
    exit;
}

$dbh = getDatabaseConnection();

$pet = getPetById($dbh, $petID);

if (!$pet) {
    header('Location: profile.php');
    exit;
}

$isOwner  = ($pet['ownerID'] == $_SESSION['userID']);
$sessions = getPetUpcomingSessions($dbh, $petID);

drawHeader('pet_profile.css');
?>

<main class="pet-profile-container">

    <section class="pet-hero">
        <div class="pet-hero-avatar">
            <img id="pet-photo-display"
                 src="./img/pets/<?= htmlspecialchars($pet['photo'] ?? 'default_pet.png') ?>"
                 alt="<?= htmlspecialchars($pet['name']) ?>"
                 onerror="this.src='./img/pets/default_pet.png'">
        </div>

        <!-- View mode -->
        <div class="pet-hero-info" id="pet-view">
            <h1 id="display-name"><?= htmlspecialchars($pet['name']) ?></h1>
            <p class="pet-breed" id="display-breed"><?= htmlspecialchars($pet['breed']) ?></p>
            <p class="pet-age" id="display-age"><?= $pet['age'] ?> years old</p>
            <span class="vacc-badge <?= $pet['vaccinated'] ? 'vacc-yes' : 'vacc-no' ?>" id="display-vacc">
                <?= $pet['vaccinated'] ? '✓ Vaccinated' : '✗ Not Vaccinated' ?>
            </span>
            <?php if ($isOwner): ?>
                <button class="edit-btn" onclick="toggleEdit(true)">Edit Pet</button>
            <?php endif; ?>
        </div>

        <!-- Edit mode -->
        <?php if ($isOwner): ?>
        <div class="pet-edit-form" id="pet-edit" style="display:none;">
            <div id="pet-feedback"></div>
            <form id="editPetForm" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="petID" value="<?= $pet['petID'] ?>">

                <div class="pet-photo-change">
                    <label for="petPhoto" class="change-photo-btn">Change Photo</label>
                    <input type="file" id="petPhoto" name="petPhoto" accept="image/*">
                    <span id="photo-chosen" style="font-size:0.85rem;color:#888;"></span>
                </div>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($pet['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Breed</label>
                    <input type="text" name="breed" value="<?= htmlspecialchars($pet['breed']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Age (years)</label>
                    <input type="number" name="age" min="0" max="30" value="<?= $pet['age'] ?>" required>
                </div>

                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" name="vaccinated" value="1" <?= $pet['vaccinated'] ? 'checked' : '' ?>>
                        Vaccinated
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-dark">Save</button>
                    <button type="button" class="btn-cancel" onclick="toggleEdit(false)">Cancel</button>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </section>

    <section class="upcoming-sessions">
        <h2>🐾 Upcoming Sessions</h2>
        <div class="sessions-container">
            <?php if (empty($sessions)): ?>
                <p>No upcoming sessions scheduled.</p>
            <?php else: ?>
                <?php foreach ($sessions as $index => $session):
                    $dateObj = new DateTime($session['start_time']);
                    $endObj  = new DateTime($session['end_time']);
                ?>
                <div class="session-card">
                    <div class="session-date-box <?= ($index % 2 !== 0) ? 'secondary' : '' ?>">
                        <span class="day"><?= $dateObj->format('d') ?></span>
                        <span class="month"><?= strtoupper($dateObj->format('M')) ?></span>
                    </div>
                    <div class="session-main">
                        <div class="session-info">
                            <h3><?= htmlspecialchars($session['title']) ?></h3>
                            <p>🕒 <?= $dateObj->format('h:i A') ?> – <?= $endObj->format('h:i A') ?></p>
                            <p>📍 <?= htmlspecialchars($session['roomName']) ?></p>
                            <p>👤 <?= htmlspecialchars($session['trainerName']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <div class="back-link">
        <a href="profile.php" class="btn btn-outline">← Back to Profile</a>
    </div>

</main>

<?php if ($isOwner): ?>
<script src="js/pet_profile.js"></script>
<?php endif; ?>

<?php drawFooter(); ?>
