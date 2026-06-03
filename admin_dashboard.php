<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'templates/common.php';
require_once 'database/connection.php';
require_once 'database/stats.php';
require_once 'database/users.php';
$dbh = getDatabaseConnection();

$member_count = getMemberCount($dbh);
$trainer_count = getTrainerCount($dbh);
$pet_count = getPetCount($dbh);
$available_equipment = getAvailableEquipmentCount($dbh);
$total_equipment = getTotalEquipmentCount($dbh);
$class_count = getUpcomingClassCount($dbh);
$admin_count = getAdminCount($dbh);

$admin_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin';
$first_name = explode(' ', $admin_name)[0];

drawHeader('admin_dashboard.css');
?>

<main>
    <section class="admin-header">
        <h1>Admin Dashboard</h1>
        <p>Welcome back, <?= htmlspecialchars($first_name) ?>! Manage your platform here.</p>
    </section>

    <?php drawSessionMessages(); ?>

    <section class="admin-stats">
        <h2>Platform Overview</h2>
        <div class="stats">
            <div class="stat-item">
                <h2 data-target="<?= $member_count ?>" data-suffix="">0</h2>
                <p>Active Members</p>
            </div>

            <div class="stat-item">
                <h2 data-target="<?= $trainer_count ?>" data-suffix="">0</h2>
                <p>Trainers & Pet Trainers</p>
            </div>

            <div class="stat-item">
                <h2 data-target="<?= $pet_count ?>" data-suffix="">0</h2>
                <p>Registered Pets</p>
            </div>

            <div class="stat-item">
                <h2 data-target="<?= $available_equipment ?>" data-suffix="/<?= $total_equipment ?>">0</h2>
                <p>Equipment Available</p>
            </div>

            <div class="stat-item">
                <h2 data-target="<?= $class_count ?>" data-suffix="">0</h2>
                <p>Upcoming Classes</p>
            </div>

            <div class="stat-item">
                <h2 data-target="<?= $admin_count ?>" data-suffix="">0</h2>
                <p>Admins</p>
            </div>
        </div>
    </section>

    <section class="admin-features">
        <h2>Management Tools</h2>
        <div class="features">
            <div class="feature-item">
                <h3>Users</h3>
                <p>Create, update, and deactivate user accounts. Manage all user profiles and role permissions.</p>
                <div class="feature-buttons">
                    <a href="manage_users.php" class="btn btn-primary">View All</a>
                </div>
            </div>

            <div class="feature-item">
                <h3>Classes</h3>
                <p>Manage the class catalog. Create, edit, and remove classes. Assign trainers to classes.</p>
                <div class="feature-buttons">
                    <a href="classes.php" class="btn btn-primary">View All</a>
                </div>
            </div>

            <div class="feature-item">
                <h3>Equipment</h3>
                <p>Manage equipment inventory. Add, update availability status, and remove items.</p>
                <div class="feature-buttons">
                    <a href="equipment.php" class="btn btn-primary">View All</a>
                </div>
            </div>

            <div class="feature-item">
                <h3>Pet Rooms</h3>
                <p>Oversee pet room sessions and manage pet care operations.</p>
                <div class="feature-buttons">
                    <a href="pet_rooms.php" class="btn btn-primary">View All</a>
                </div>
            </div>

                <div class="feature-item">
                    <h3>System</h3>
                    <p>Ensure smooth operation of the entire platform. Monitor system health and logs.</p>
                    <div class="feature-buttons">
                        <a href="system_status.php" class="btn btn-primary">System Status</a>
                    </div>
                </div>

            <div class="feature-item">
                <h3>Newsletter</h3>
                <p>View and manage all members subscribed to the platform newsletter.</p>
                <div class="feature-buttons">
                    <a href="newsletter_subscribers.php" class="btn btn-primary">View Subscribers</a>
                </div>
            </div>
            </div>
        </div>
    </section>
</main>

<script src="js/stats_counter.js?v=<?= time() ?>"></script>

<?php
drawFooter();
?>