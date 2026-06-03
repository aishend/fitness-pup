<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'templates/common.php';
require_once 'database/connection.php';
require_once 'database/equipment.php';

$dbh = getDatabaseConnection();

$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';

$human_equipment = getEquipmentByGroup($dbh, 'Human');
$pet_equipment   = getEquipmentByGroup($dbh, 'Pet');

$human_grouped = [];
foreach ($human_equipment as $item) {
    $human_grouped[$item['category']][] = $item;
}

$pet_grouped = [];
foreach ($pet_equipment as $item) {
    $pet_grouped[$item['category']][] = $item;
}

$all_categories = getAllEquipmentCategories($dbh);

drawHeader('equipment.css');
?>

<section class="equipment-hero">
    <h1>Our Equipment</h1>
    <p>Everything you and your pup need — browse what's available right now.</p>
</section>

<main class="equipment-wrapper">

    <div class="equipment-controls">
        <div class="controls-left">
            <div class="toggle-group">
                <button class="toggle-btn active" id="btn-gym" onclick="switchGroup('gym')">🏋️ Gym</button>
                <button class="toggle-btn" id="btn-pup" onclick="switchGroup('pup')">🐾 Pup</button>
            </div>
            <label class="available-filter">
                <input type="checkbox" id="available-only" onchange="applyFilter()">
                Show available only
            </label>
        </div>

        <?php if ($user_role === 'admin'): ?>
            <button type="button" onclick="openEquipmentModal('create')" class="btn btn-dark">+ Add Equipment</button>
        <?php endif; ?>
    </div>

    <div id="group-gym">
        <?php foreach ($human_grouped as $category => $items): ?>
            <div class="equipment-category">
                <h2><?= htmlspecialchars($category) ?></h2>
                <div class="equipment-grid">
                    <?php foreach ($items as $item): ?>
                        <div class="equipment-card" data-status="<?= $item['status'] ?>">
                            <div class="equipment-img">
                                <?php if (!empty($item['image']) && file_exists("./img/equipment/" . $item['image'])): ?>
                                    <img src="./img/equipment/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                <?php else: ?>
                                    <img src="./img/not_available.png" alt="<?= htmlspecialchars($item['name']) ?>">
                                <?php endif; ?>
                            </div>
                            <div class="equipment-body">
                                <div class="equipment-header">
                                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                                    <span class="status-badge status-<?= $item['status'] ?>">
                                        <?= ucfirst(str_replace('_', ' ', $item['status'])) ?>
                                    </span>
                                </div>
                                <p><?= htmlspecialchars($item['description']) ?></p>

                                <?php if ($user_role === 'admin'): ?>
                                    <div class="card-actions">
                                        <?php
                                        $eqID = $item['equipmentID'] ?? $item['id'];
                                        $catID = $item['categoryID'] ?? '';
                                        $clickParams = sprintf(
                                            "openEquipmentModal('edit', '%s', '%s', '%s', '%s', '%s')",
                                            $eqID,
                                            htmlspecialchars($item['name'], ENT_QUOTES),
                                            htmlspecialchars($item['description'] ?? '', ENT_QUOTES),
                                            $catID,
                                            $item['status']
                                        );
                                        ?>
                                        <button type="button" onclick="<?= $clickParams ?>" class="btn btn-action btn-primary">Edit</button>
                                        <button type="button" class="btn btn-action btn-danger" onclick="openDeleteEquipmentModal(<?= $eqID ?>, '<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>')">Delete</button>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="group-pup" style="display:none;">
        <?php foreach ($pet_grouped as $category => $items): ?>
            <div class="equipment-category">
                <h2><?= htmlspecialchars($category) ?></h2>
                <div class="equipment-grid">
                    <?php foreach ($items as $item): ?>
                        <div class="equipment-card" data-status="<?= $item['status'] ?>">
                            <div class="equipment-img">
                                <?php if (!empty($item['image']) && file_exists("./img/equipment/" . $item['image'])): ?>
                                    <img src="./img/equipment/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                <?php else: ?>
                                    <img src="./img/not_available.png" alt="<?= htmlspecialchars($item['name']) ?>">
                                <?php endif; ?>
                            </div>
                            <div class="equipment-body">
                                <div class="equipment-header">
                                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                                    <span class="status-badge status-<?= $item['status'] ?>">
                                        <?= ucfirst(str_replace('_', ' ', $item['status'])) ?>
                                    </span>
                                </div>
                                <p><?= htmlspecialchars($item['description']) ?></p>

                                <?php if ($user_role === 'admin'): ?>
                                    <div class="card-actions">
                                        <?php
                                        $eqID = $item['equipmentID'] ?? $item['id'];
                                        $catID = $item['categoryID'] ?? '';
                                        $clickParams = sprintf(
                                            "openEquipmentModal('edit', '%s', '%s', '%s', '%s', '%s')",
                                            $eqID,
                                            htmlspecialchars($item['name'], ENT_QUOTES),
                                            htmlspecialchars($item['description'] ?? '', ENT_QUOTES),
                                            $catID,
                                            $item['status']
                                        );
                                        ?>
                                        <button type="button" onclick="<?= $clickParams ?>" class="btn btn-action btn-primary">Edit</button>
                                        <button type="button" class="btn btn-action btn-danger" onclick="openDeleteEquipmentModal(<?= $eqID ?>, '<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>')">Delete</button>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</main>

<div id="equipmentModal" class="modal-overlay" onclick="handleOverlayClick(event, 'equipmentModal')">
    <div class="modal-box">
        <div class="modal-header">
            <h2 id="modal-title">Equipment</h2>
            <button type="button" class="btn-text-only" style="font-size: 1.5rem; text-decoration: none; color: var(--color-dark);" onclick="closeEquipmentModal()">&times;</button>
        </div>
        <form method="POST" action="actions/admin/action_equipment.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="action" id="modal-action">
            <input type="hidden" name="equipmentID" id="modal-id">

            <div class="modal-body">
                <div class="input-group">
                    <label>Name</label>
                    <input type="text" name="name" id="modal-name" required>
                </div>
                <div class="input-group">
                    <label>Category</label>
                    <select name="categoryID" id="modal-category" required>
                        <option value="">Select a category</option>
                        <?php foreach ($all_categories as $cat): ?>
                            <option value="<?= $cat['categoryID'] ?>">
                                <?= htmlspecialchars($cat['name']) ?> (<?= htmlspecialchars($cat['targetGroup']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label>Status</label>
                    <select name="status" id="modal-status" required>
                        <option value="available">Available</option>
                        <option value="in_use">In Use</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Description</label>
                    <textarea name="description" id="modal-description" rows="3"></textarea>
                </div>
                <div class="input-group">
                    <label>Image</label>
                    <input type="file" name="image" accept="image/*">
                </div>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" onclick="closeEquipmentModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="equipmentDeleteModal" class="modal-overlay" onclick="handleOverlayClick(event, 'equipmentDeleteModal')">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Confirm Deletion</h2>
        </div>
        <form method="POST" action="actions/admin/action_equipment.php">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="equipmentID" id="delete-equipment-id">
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="delete-equipment-name"></strong>?</p>
                <p class="modal-sub">This action cannot be undone.</p>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                <button type="button" class="btn btn-secondary" onclick="closeDeleteEquipmentModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="js/equipment.js"></script>

<?php drawFooter(); ?>