<?php
session_start();

require_once 'templates/common.php';
require_once 'database/connection.php';
require_once 'database/details.php';
require_once 'database/classes.php';

// Trainer only
if (!isset($_SESSION['userID']) || !in_array($_SESSION['role'], ['trainer', 'pet-trainer'])) {
    header('Location: index.php');
    exit;
}

$dbh = getDatabaseConnection();
$isPetSession = isset($_GET['sessionID']);

if ($isPetSession) {
    $sessionID = intval($_GET['sessionID']);
    $sessionDetails = getPetSessionDetails($dbh, $sessionID);
    $roster = getPetSessionRoster($dbh, $sessionID);

    // Pet-trainers may only view their own sessions
    if ($sessionDetails && $_SESSION['role'] === 'pet-trainer') {
        if ((int)$sessionDetails['trainerID'] !== (int)$_SESSION['userID']) {
            header('Location: profile.php');
            exit;
        }
    }
} else {
    $classID = intval($_GET['classID']);
    $sessionDetails = getClassDetails($dbh, $classID);
    $roster = getClassRoster($dbh, $classID);

    // Trainers may only view their own class rosters
    if ($sessionDetails && $_SESSION['role'] === 'trainer') {
        $myTrainerID = getTrainerIDByUserID($dbh, (int)$_SESSION['userID']);
        if (!$myTrainerID || (int)$sessionDetails['trainerID'] !== $myTrainerID) {
            header('Location: profile.php');
            exit;
        }
    }
}

if (!$sessionDetails) {
    header('Location: profile.php');
    exit;
}

drawHeader('session_details.css');
?>

<section class="roster-hero">
    <a href="profile.php" class="back-link">← Back to Profile</a>
    <?php if (!$isPetSession && !empty($sessionDetails['class_image'])): ?>
        <img src="./img/classes/<?= htmlspecialchars($sessionDetails['class_image']) ?>" alt="<?= htmlspecialchars($sessionDetails['name'] ?? '') ?>" class="session-detail-image">
    <?php endif; ?>
    <h1><?= htmlspecialchars($sessionDetails['name'] ?? $sessionDetails['title']) ?></h1>
    <?php
        $start = new DateTime($sessionDetails['start_time']);
        $end   = new DateTime($sessionDetails['end_time']);
    ?>
    <p>
        <?= $start->format('l, j F Y') ?> &bull;
        <?= $start->format('H:i') ?> - <?= $end->format('H:i') ?>
        <?php if ($isPetSession): ?>
            &bull; <?= htmlspecialchars($sessionDetails['roomName']) ?>
        <?php endif; ?>
    </p>
</section>

<main class="roster-wrapper">

    <div class="roster-summary">
        <div class="summary-card">
            <h3><?= count($roster) ?></h3>
            <p><?= $isPetSession ? 'Pets Enrolled' : 'Members Enrolled' ?></p>
        </div>
        <div class="summary-card">
            <h3><?= htmlspecialchars($sessionDetails['capacity']) ?></h3>
            <p>Total Capacity</p>
        </div>
        <div class="summary-card">
            <h3><?= $sessionDetails['capacity'] - count($roster) ?></h3>
            <p>Spots Remaining</p>
        </div>
    </div>

    <?php if (empty($roster)): ?>
        <p class="no-roster">No <?= $isPetSession ? 'pets' : 'members' ?> enrolled yet.</p>
    <?php else: ?>
        <div class="roster-table-wrapper" data-paginator data-paginator-items="tbody tr">
            <table class="roster-table">
                <thead>
                    <tr>
                        <?php if ($isPetSession): ?>
                            <th>Pet</th>
                            <th>Breed</th>
                            <th>Age</th>
                            <th>Vaccinated</th>
                            <th>Owner</th>
                            <th>Enrolled On</th>
                        <?php else: ?>
                            <th>Member</th>
                            <th>Username</th>
                            <th>Enrolled On</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roster as $row): ?>
                        <tr>
                            <?php if ($isPetSession): ?>
                                <td>
                                    <div class="member-cell">
                                        <span><?= htmlspecialchars($row['petName']) ?></span>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($row['breed']) ?></td>
                                <td><?= htmlspecialchars($row['age']) ?> yrs</td>
                                <td>
                                    <span class="vacc-badge <?= $row['vaccinated'] ? 'vacc-yes' : 'vacc-no' ?>">
                                        <?= $row['vaccinated'] ? '✓ Yes' : '✗ No' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="member-cell">
                                        <img src="./img/users/<?= htmlspecialchars($row['profilePhoto'] ?? 'user-avatar.png') ?>" alt="">
                                        <span><?= htmlspecialchars($row['ownerName']) ?></span>
                                    </div>
                                </td>
                            <?php else: ?>
                                <td>
                                    <div class="member-cell">
                                        <img src="./img/users/<?= htmlspecialchars($row['profilePhoto'] ?? 'user-avatar.png') ?>" alt="">
                                        <span><?= htmlspecialchars($row['name']) ?></span>
                                    </div>
                                </td>
                                <td>@<?= htmlspecialchars($row['username']) ?></td>
                            <?php endif; ?>
                            <td><?= (new DateTime($row['enrollment_date']))->format('j M Y') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</main>

<?php drawFooter(); ?>