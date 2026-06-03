<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'templates/common.php';
require_once 'database/connection.php';
require_once 'database/trainers.php';

$dbh = getDatabaseConnection();

$gym_trainers = getTrainersByRole($dbh, 'trainer');
$pet_trainers = getTrainersByRole($dbh, 'pet-trainer');

foreach ($gym_trainers as &$trainer) {
    $trainer['classes'] = $trainer['trainerID']
        ? getUpcomingClassesForTrainer($dbh, $trainer['trainerID'])
        : [];
}
unset($trainer);

foreach ($pet_trainers as &$trainer) {
    $trainer['classes'] = $trainer['trainerID']
        ? getUpcomingClassesForTrainer($dbh, $trainer['trainerID'])
        : [];
}
unset($trainer);

drawHeader('trainers.css');
?>

<section class="trainers-hero">
    <h1>Meet Our Trainers</h1>
    <p>Expert professionals dedicated to you and your pup's fitness journey.</p>
</section>

<main class="trainers-wrapper">

    <div class="trainers-controls">
        <div class="toggle-group">
            <button class="toggle-btn active" id="btn-gym" onclick="switchGroup('gym')">🏋️ Gym Trainers</button>
            <button class="toggle-btn" id="btn-pup" onclick="switchGroup('pup')">🐾 Pet Trainers</button>
        </div>
    </div>

    <div id="group-gym">
        <div class="trainers-grid">
            <?php foreach ($gym_trainers as $trainer): ?>
                <div class="trainer-card">
                    <div class="trainer-img">
                        <?php
                            $tp = $trainer['profilePhoto'] ?? '';
                            $tPath = "./img/users/$tp";
                            if (empty($tp) || !file_exists($tPath)) $tPath = "./img/users/user-avatar.png";
                        ?>
                        <img src="<?= htmlspecialchars($tPath) ?>" alt="<?= htmlspecialchars($trainer['name']) ?>">
                    </div>
                    <div class="trainer-body">
                        <h3><?= htmlspecialchars($trainer['name']) ?></h3>
                        <?php if (!empty($trainer['specialty'])): ?>
                            <p class="trainer-specialty">💪 <?= htmlspecialchars($trainer['specialty']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($trainer['certifications'])): ?>
                            <p class="trainer-cert">🎓 <?= htmlspecialchars($trainer['certifications']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($trainer['bio'])): ?>
                            <p class="trainer-bio"><?= htmlspecialchars($trainer['bio']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($trainer['classes'])): ?>
                            <div class="trainer-classes">
                                <p class="classes-label">Upcoming Classes</p>
                                <div class="class-tags">
                                    <?php foreach ($trainer['classes'] as $class): ?>
                                        <span class="class-tag"><?= htmlspecialchars($class['name']) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <a href="profile.php?id=<?= $trainer['userID'] ?>" class="btn btn-dark trainer-btn">View Profile</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="group-pup" style="display:none;">
        <div class="trainers-grid">
            <?php foreach ($pet_trainers as $trainer): ?>
                <div class="trainer-card">
                    <div class="trainer-img">
                        <?php
                            $tp = $trainer['profilePhoto'] ?? '';
                            $tPath = "./img/users/$tp";
                            if (empty($tp) || !file_exists($tPath)) $tPath = "./img/users/user-avatar.png";
                        ?>
                        <img src="<?= htmlspecialchars($tPath) ?>" alt="<?= htmlspecialchars($trainer['name']) ?>">
                    </div>
                    <div class="trainer-body">
                        <h3><?= htmlspecialchars($trainer['name']) ?></h3>
                        <?php if (!empty($trainer['specialty'])): ?>
                            <p class="trainer-specialty">🐾 <?= htmlspecialchars($trainer['specialty']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($trainer['certifications'])): ?>
                            <p class="trainer-cert">🎓 <?= htmlspecialchars($trainer['certifications']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($trainer['bio'])): ?>
                            <p class="trainer-bio"><?= htmlspecialchars($trainer['bio']) ?></p>
                        <?php endif; ?>
                        <a href="profile.php?id=<?= $trainer['userID'] ?>" class="btn btn-dark trainer-btn">View Profile</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</main>

<script src="js/trainers.js"></script>

<?php drawFooter(); ?>