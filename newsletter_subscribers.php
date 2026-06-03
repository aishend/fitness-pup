<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'templates/common.php';
require_once 'database/connection.php';
require_once 'database/newsletter.php';

$dbh = getDatabaseConnection();
$subscribers = getAllNewsletterSubscribers($dbh);

drawHeader('newsletter_subscribers.css');
?>

<section class="newsletter-hero">
    <h1>Newsletter Subscribers</h1>
    <p>All members who have signed up to receive platform news and updates.</p>
</section>

<main class="newsletter-container">

    <div class="newsletter-card">
        <h2>Summary</h2>
        <div class="status-list">
            <div class="status-item">
                <span class="label">Total Subscribers</span>
                <span class="value"><?= count($subscribers) ?></span>
            </div>
            <?php if (!empty($subscribers)): ?>
            <div class="status-item">
                <span class="label">Most Recent</span>
                <span class="value"><?= htmlspecialchars($subscribers[0]['subscribed_at']) ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="newsletter-card">
        <h2>Subscriber List</h2>
        <?php if (empty($subscribers)): ?>
            <p>No subscribers yet.</p>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="subscribers-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Subscribed at</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subscribers as $i => $sub): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($sub['email']) ?></td>
                                <td><?= htmlspecialchars($sub['subscribed_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</main>

<?php drawFooter(); ?>
