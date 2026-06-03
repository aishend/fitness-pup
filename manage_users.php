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
require_once 'database/users.php';
require_once 'database/admin.php';

$dbh = getDatabaseConnection();

$filter = isset($_GET['role']) ? $_GET['role'] : 'all';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$users = getUsersFiltered($dbh, $filter, $search);

drawHeader('manage_users.css');
?>

<main>
    <section class="manage-header">
        <h1>Manage Users</h1>
        <p>Create, update, and deactivate user accounts.</p>
    </section>

    <?php drawSessionMessages(); ?>

    <section class="manage-controls">
        <div class="filter-buttons">
            <a href="manage_users.php?role=all" class="btn btn-action <?= $filter === 'all' ? 'btn-primary' : 'btn-secondary' ?>">All Users</a>
            <a href="manage_users.php?role=member" class="btn btn-action <?= $filter === 'member' ? 'btn-primary' : 'btn-secondary' ?>">Members</a>
            <a href="manage_users.php?role=trainer" class="btn btn-action <?= $filter === 'trainer' ? 'btn-primary' : 'btn-secondary' ?>">Trainers</a>
            <a href="manage_users.php?role=pet-trainer" class="btn btn-action <?= $filter === 'pet-trainer' ? 'btn-primary' : 'btn-secondary' ?>">Pet Trainers</a>
            <a href="manage_users.php?role=admin" class="btn btn-action <?= $filter === 'admin' ? 'btn-primary' : 'btn-secondary' ?>">Admins</a>
        </div>

        <form method="GET" action="manage_users.php" class="search-form">
            <input type="hidden" name="role" value="<?= htmlspecialchars($filter) ?>">
            <input type="text" name="search" placeholder="Search users..." value="<?= htmlspecialchars($search) ?>">

            <button type="submit" class="btn btn-primary">Search</button>
            <?php if ($search): ?>
                <a href="manage_users.php?role=<?= htmlspecialchars($filter) ?>" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
            <button type="button" onclick="openUserModal('create')" class="btn btn-dark">+ New User</button>
        </form>
    </section>

    <section class="users-list">
        <?php if (count($users) > 0): ?>
            <div class="users-grid" data-paginator>
                <?php foreach ($users as $user): ?>
                    <div class="user-card">
                        <div class="user-info">
                            <div class="user-name"><?= htmlspecialchars($user['name']) ?></div>
                            <div class="user-email"><?= htmlspecialchars($user['email']) ?></div>
                        </div>

                        <div class="user-role">
                            <span class="role-badge <?= $user['role'] ?>">
                                <?= ucfirst(str_replace('-', ' ', $user['role'])) ?>
                            </span>
                        </div>

                        <div class="user-actions">
                            <button type="button" onclick="openUserModal('edit', '<?= $user['userID'] ?>', '<?= htmlspecialchars($user['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>', '<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>', '<?= $user['role'] ?>')" class="btn btn-primary btn-action">Edit</button>

                            <?php if ($user['userID'] !== (int)$_SESSION['userID']): ?>
                                <form id="delete-form-<?= $user['userID'] ?>" method="POST" action="actions/admin/action_delete_user.php" class="delete-form">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="userID" value="<?= $user['userID'] ?>">
                                    <button type="button" class="btn btn-danger btn-action" onclick="openDeleteModal(<?= $user['userID'] ?>, '<?= htmlspecialchars($user['name'], ENT_QUOTES) ?>')">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>No users found matching your criteria.</p>
                <a href="manage_users.php" class="btn">Clear Filters</a>
            </div>
        <?php endif; ?>
    </section>
</main>

<div id="userModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h2 id="modal-title">User</h2>
        </div>
        <form method="POST" action="actions/admin/action_save_user.php">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="action" id="modal-action">
            <input type="hidden" name="userID" id="modal-user-id">

            <div class="modal-body">
                <div class="input-group">
                    <label>Name</label>
                    <input type="text" name="name" id="modal-name" required>
                </div>
                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" id="modal-username" required>
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" id="modal-email" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" id="modal-password">
                </div>
                <div class="input-group">
                    <label>Role</label>
                    <select name="role" id="modal-role" required>
                        <option value="member">Member</option>
                        <option value="trainer">Trainer</option>
                        <option value="pet-trainer">Pet Trainer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" onclick="closeUserModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="modal-overlay">
    <div class="modal-box modal-confirm">
        <div class="modal-header">
            <h2>Confirm Deletion</h2>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete <strong id="delete-user-name"></strong>?</p>
            <p class="text-warning">This action cannot be undone.</p>
        </div>
        <div class="modal-actions">
            <button type="button" id="confirm-delete-btn" class="btn btn-danger">Yes, Delete</button>
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
        </div>
    </div>
</div>

<script src="js/manage_users.js"></script>

<?php drawFooter(); ?>