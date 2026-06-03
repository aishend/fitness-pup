<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'templates/common.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'member') {
    header('Location: login.php');
    exit;
}

$from_prompt = isset($_GET['from']) && $_GET['from'] === 'prompt';

drawHeader('register_pet.css');
?>

<section class="register-pet-hero">
    <h1>Register Your Pet</h1>
    <p>Add your furry friend so you can book them into our pet care rooms.</p>
</section>

<main class="register-pet-wrapper">

    <div id="feedback-message"></div>

    <form id="registerPetForm" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="from_prompt" value="<?= $from_prompt ? '1' : '0' ?>">

        <div class="pet-photo-upload">
            <img id="pet-photo-preview" src="./img/pets/default_pet.png" alt="Pet Photo Preview">
            <label for="petPhoto" class="edit-btn">Choose Photo</label>
            <input type="file" id="petPhoto" name="petPhoto" accept="image/*">
        </div>

        <div class="form-group">
            <label for="name">Pet Name</label>
            <input type="text" id="name" name="name" placeholder="e.g. Buddy" required>
        </div>

        <div class="form-group">
            <label for="breed">Breed</label>
            <input type="text" id="breed" name="breed" placeholder="e.g. Golden Retriever" required>
        </div>

        <div class="form-group">
            <label for="age">Age (years)</label>
            <input type="number" id="age" name="age" min="0" max="30" placeholder="e.g. 3" required>
        </div>

        <div class="form-group checkbox-group">
            <label>
                <input type="checkbox" id="vaccinated" name="vaccinated" value="1">
                My pet is vaccinated
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-dark">Register Pet</button>
            <a href="<?= $from_prompt ? 'classes.php' : 'profile.php' ?>" class="btn btn-cancel">Cancel</a>
        </div>
    </form>

</main>

<script src="js/register_pet.js"></script>

<?php drawFooter(); ?>