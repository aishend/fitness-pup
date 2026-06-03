<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'templates/common.php';
require_once 'database/connection.php';
require_once 'database/users.php';

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}
$userID = $_SESSION['userID'];

try {
    $db = getDatabaseConnection();
    $user = getUserProfileById($db, $userID);

    if (!$user) {
        die("Error: User not found.");
    }

} catch (PDOException $e) {
    die("Critical Error: " . $e->getMessage());
}

drawHeader('edit_profile.css');
?>
<main class="profile-container">
    <section class="edit-profile-section">
        <h2>Edit Profile</h2>

        <div id="feedback-message"></div>

        <form id="editProfileForm" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="avatar-upload">
                <?php
                    $epPhoto = $user['profilePhoto'] ?? '';
                    $epPath  = "./img/users/" . $epPhoto;
                    if (empty($epPhoto) || !file_exists($epPath) || !is_file($epPath)) {
                        $epPath = "./img/users/user-avatar.png";
                    }
                ?>
                <img id="avatar-preview" src="<?= htmlspecialchars($epPath) ?>" alt="Preview">
                <label for="profilePhoto" class="edit-btn">Change Photo</label>
                <input type="file" id="profilePhoto" name="profilePhoto" accept="image/*" class="hidden-file-input">
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="4"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
            </div>

        <?php if ($_SESSION['role'] === 'trainer' || $_SESSION['role'] === 'pet-trainer'): ?>
            <hr class="form-divider">
            <p class="form-section-title">Trainer Info</p>

            <div class="form-group">
                <label for="specialty">Specialty</label>
                <input type="text" id="specialty" name="specialty" value="<?php echo htmlspecialchars($trainerData['specialty'] ?? ''); ?>" placeholder="e.g., Weightloss, Yoga, Pet Agility">
            </div>

            <div class="form-group">
                <label for="certifications">Certifications</label>
                <textarea id="certifications" name="certifications" rows="3" placeholder="e.g., Certified Personal Trainer, Canine Behavior Specialist"><?php echo htmlspecialchars($trainerData['certifications'] ?? ''); ?></textarea>
            </div>
        <?php endif; ?>

            <hr class="form-divider">
            <p class="form-section-title">Change Password</p>

        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" placeholder="Enter current password">
        </div>

        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password">
        </div>

            <div class="form-actions">
                <button type="submit" class="edit-btn">Save Changes</button>
                <a href="profile.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </section>
</main>

<script src="js/edit_profile.js"></script>

<?php drawFooter(); ?>