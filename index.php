<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'templates/common.php';
require_once 'database/connection.php';
require_once 'database/stats.php';
require_once 'database/users.php';

$dbh = getDatabaseConnection();
$member_count = getMemberCount($dbh);
$trainer_count = getTrainerCount($dbh);
$class_count = getUpcomingClassCount($dbh);

$is_logged_in = isset($_SESSION['userID']);
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$full_name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$first_name = $full_name ? explode(' ', $full_name)[0] : '';

$user_email = '';
if ($is_logged_in) {
    $user_profile = getUserProfileById($dbh, $_SESSION['userID']);
    $user_email = $user_profile['email'] ?? '';
}

drawHeader('index.css');
?>

<main>
    <?php drawSessionMessages(); ?>
    <section class="motivator">
        <div class="motivator-content">
            <?php if ($is_logged_in): ?>
                <h1>Welcome back, <?= htmlspecialchars($first_name) ?>!</h1>
                <p>Ready to crush today's goals? Book a class, check your schedule, or reserve a spot for your furry friend.</p>
            <?php else: ?>
                <h1>Transform Your Body, Your Pup Stays Happy!</h1>
                <p>Join Fitness Pup - where your fitness goals meet pet-friendly care! Access expert trainers, state-of-the-art equipment, and dynamic classes while your furry friend enjoys our safe pet care space.</p>
            <?php endif; ?>
        </div>

        <div class="motivator-buttons">
            <?php if ($is_logged_in): ?>
                <?php if ($user_role === 'admin'): ?>
                    <a href="admin_dashboard.php" class="btn btn-primary">Admin Dashboard</a>
                <?php else: ?>
                    <a href="profile.php" class="btn btn-primary">My Profile</a>
                <?php endif; ?>
                <a href="classes.php" class="btn btn-primary">Book Classes</a>
                <a href="pet_rooms.php" class="btn btn-primary">Pet Rooms</a>
            <?php else: ?>
                <a href="register.php" class="btn btn-primary">Get Started</a>
                <a href="login.php" class="btn btn-primary">Login</a>
                <a href="about.php" class="btn btn-primary">Learn More</a>
            <?php endif; ?>
        </div>
    </section>

    <section class="stats">
        <div class="stat-item">
            <h2 data-target="<?= $member_count ?>" data-suffix="+">0</h2>
            <p>Active Members</p>
        </div>
        <div class="stat-item">
            <h2 data-target="<?= $trainer_count ?>" data-suffix="+">0</h2>
            <p>Expert Trainers</p>
        </div>
        <div class="stat-item">
            <h2 data-target="<?= $class_count ?>" data-suffix="+">0</h2>
            <p>Classes Weekly</p>
        </div>
        <div class="stat-item">
            <h2>24/7</h2>
            <p>Access</p>
        </div>
    </section>

    <section class="features">
        <h2>Why Choose <img src="./img/logo.png" alt="Fitness Pup Logo" style="height: 1em; vertical-align: middle;">?</h2>
        <p>Everything you need to achieve your fitness goals - including care for your furry friends</p>
        <div class="feature-item">
            <h3>Flexible Schedule</h3>
            <p>Browse and book classes that fit your busy lifestyle</p>
        </div>
        <div class="feature-item">
            <h3>Expert Trainers</h3>
            <p>Learn from certified professionals with years of experience</p>
        </div>
        <div class="feature-item">
            <h3>Modern Equipment</h3>
            <p>Track equipment availability in real-time</p>
        </div>
        <div class="feature-item">
            <h3>Pet Care Space</h3>
            <p>Safe, comfortable care for your pets while you workout!</p>
        </div>
    </section>

    <section class="newsletter">
        <h2>Stay in the Loop!</h2>
        <p>Get exclusive workout tips and pet-friendly event alerts delivered to your inbox.</p>
        <form id="newsletter-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="email" name="email" id="email-newsletter"
                <?php if ($user_email): ?>
                    value="<?= htmlspecialchars($user_email) ?>" class="prefilled"
                <?php else: ?>
                    placeholder="Your Email Address"
                <?php endif; ?>
                required>
            <button type="submit" class="btn btn-dark">Join</button>
        </form>
        <p id="newsletter-feedback" aria-live="polite"></p>
    </section>

    <section class="motivator2">
        <?php if ($is_logged_in): ?>
            <h2>Keep the Momentum Going!</h2>
            <p>Don't let your streak break. Check out what classes are happening today.</p>
            <a href="classes.php" class="btn btn-dark">View Schedule</a>
        <?php else: ?>
            <h2>Ready to Start Your Fitness Journey?</h2>
            <p>Join hundreds of members who have already transformed their lives!</p>
            <a href="register.php" class="btn btn-dark">Register Now</a>
            <a href="contact.php" class="btn btn-dark">Contact Us</a>
        <?php endif; ?>
    </section>

</main>

<script src="js/newsletter.js"></script>
<script src="js/stats_counter.js"></script>
<?php
drawFooter();
?>