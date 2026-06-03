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
require_once 'database/system.php';

$dbh = getDatabaseConnection();

$php_version = phpversion();
$server_software = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
$sqlite_version = getSqliteVersion($dbh);

$logs = getRecentActivityLogs($dbh);

drawHeader('system_status.css');
?>

<section class="system-hero">
    <h1>System Status & Logs</h1>
    <p>Monitor platform health, server specifications, and recent activity.</p>
</section>

<main class="system-container">

    <div class="system-card">
        <h2>System Health</h2>
        <div class="status-list">
            <div class="status-item">
                <span class="label">Database Connection</span>
                <span class="value"><div class="status-dot"></div> Online</span>
            </div>
            <div class="status-item">
                <span class="label">PHP Version</span>
                <span class="value"><?= htmlspecialchars($php_version) ?></span>
            </div>
            <div class="status-item">
                <span class="label">SQLite Version</span>
                <span class="value"><?= htmlspecialchars($sqlite_version) ?></span>
            </div>
            <div class="status-item">
                <span class="label">Server Software</span>
                <span class="value"><?= htmlspecialchars($server_software) ?></span>
            </div>
            <div class="status-item">
                <span class="label">Current Server Time</span>
                <span class="value"><?= date('Y-m-d H:i:s') ?></span>
            </div>
        </div>
    </div>

    <div class="system-card">
        <h2>Recent Activity Logs</h2>
        <?php if (empty($logs)): ?>
            <p>No recent activity detected.</p>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="logs-table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>User</th>
                            <th>Target</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><span class="log-badge"><?= htmlspecialchars($log['action']) ?></span></td>
                                <td><?= htmlspecialchars($log['user_name']) ?></td>
                                <td><?= htmlspecialchars($log['target_name']) ?></td>
                                <td><?= htmlspecialchars($log['log_date']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</main>

<?php drawFooter(); ?>