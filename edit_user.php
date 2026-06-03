<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is admin
if (!isset($_SESSION['userID']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'templates/common.php';
require_once 'database/connection.php';
require_once 'database/users.php';

$dbh = getDatabaseConnection();

$userID = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$userID) {
    header('Location: manage_users.php');
    exit;
}

$user = getUserProfileById($dbh, $userID);
if (!$user) {
    $_SESSION['error'] = 'User not found.';
    header('Location: manage_users.php');
    exit;
}

$admin_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin';

drawHeader('manage_users.css');
?>

<main>
    <section class="manage-header">
        <h1>Edit User</h1>
        <p>Update user information and settings.</p>
    </section>

    <?php drawSessionMessages(); ?>

    <section class="edit-user-container">
        <div class="edit-form-wrapper">
            <form method="POST" action="actions/admin/action_update_user.php" class="edit-form">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="userID" value="<?= $user['userID'] ?>">

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="<?= htmlspecialchars($user['name']) ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?= htmlspecialchars($user['username']) ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($user['email']) ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="member" <?= $user['role'] === 'member' ? 'selected' : '' ?>>Member</option>
                        <option value="trainer" <?= $user['role'] === 'trainer' ? 'selected' : '' ?>>Trainer</option>
                        <option value="pet-trainer" <?= $user['role'] === 'pet-trainer' ? 'selected' : '' ?>>Pet Trainer</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea
                        id="bio"
                        name="bio"
                        rows="4"
                    ><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>

            <div class="user-info-sidebar">
                <h3>User Information</h3>
                <div class="info-item">
                    <span class="label">User ID:</span>
                    <span class="value"><?= $user['userID'] ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Role:</span>
                    <span class="value role-badge role-<?= $user['role'] ?>">
                        <?= ucfirst(str_replace('-', ' ', $user['role'])) ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="label">Email:</span>
                    <span class="value"><?= htmlspecialchars($user['email']) ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Profile Photo:</span>
                    <span class="value"><?= htmlspecialchars($user['profilePhoto'] ?? 'user-avatar.png') ?></span>
                </div>
            </div>
        </div>
    </section>

</main>

<?php drawFooter(); ?>
