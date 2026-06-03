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

drawHeader('manage_users.css');
?>

<main>
    <section class="manage-header">
        <h1>Create New User</h1>
        <p>Add a new member, trainer, or pet trainer to the platform.</p>
    </section>

    <?php drawSessionMessages(); ?>

    <section class="edit-user-container">
        <div class="edit-form-wrapper">
            <form method="POST" action="actions/admin/action_create_user.php" class="edit-form">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="username">Username *</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="">Select a role</option>
                        <option value="member">Member</option>
                        <option value="trainer">Trainer</option>
                        <option value="pet-trainer">Pet Trainer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea
                        id="bio"
                        name="bio"
                        rows="4"
                        placeholder="Optional: User biography"
                    ></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Create User</button>
                    <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>

            <div class="user-info-sidebar">
                <h3>Requirements</h3>
                <div class="info-item">
                    <span class="label">Username:</span>
                    <span class="value">Must be unique</span>
                </div>
                <div class="info-item">
                    <span class="label">Email:</span>
                    <span class="value">Must be valid and unique</span>
                </div>
                <div class="info-item">
                    <span class="label">Password:</span>
                    <span class="value">Min 6 characters</span>
                </div>
                <div class="info-item">
                    <span class="label">Role:</span>
                    <span class="value">Member, Trainer, or Pet Trainer</span>
                </div>
            </div>
        </div>
    </section>

</main>

<?php drawFooter(); ?>
