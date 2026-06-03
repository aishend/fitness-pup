<?php
  session_start();

  require_once 'database/connection.php';
  require_once 'database/users.php';
  require_once 'templates/common.php';

  $dbh = getDatabaseConnection();
  $pet_trainers = getPetTrainers($dbh);

  drawHeader('about.css');
?>

<section class="motivator">
    <div class="motivator-content">
        <h1>Our Pet Care Service</h1>
        <p>You focus on your training,<br>
        We take care of your puppy!</p>
    </div>
</section>

<section class="workflow">
    <h2>How It Works</h2>
    <div class="steps">
        <div class="step">
          <h3>1. Drop Off</h3>
          <p>Drop off your pup at our pet care area.</p>
        </div>

        <div class="step">
          <h3>2. Work Out</h3>
          <p>Enjoy your workout stress-free.</p>
        </div>

        <div class="step">
          <h3>3. Pick Up</h3>
          <p>Pick up a happy and relaxed companion!</p>
        </div>
      </div>
</section>

<section class="services">
    <h2>What We Offer</h2>
    <div class="services-grid">
        <div class="service-item">
            <h3>Supervised Play Area</h3>
            <p>Your pup can play safely with other dogs under professional supervision.</p>
        </div>
        <div class="service-item">
            <h3>Rest & Relaxation Zones</h3>
            <p>Comfortable spaces for your pet to relax while waiting for you.</p>
        </div>
        <div class="service-item">
            <h3>Feeding & Hydration</h3>
            <p>We ensure your pet stays hydrated and fed if needed.</p>
        </div>
        <div class="service-item">
            <h3>Trained Staff</h3>
            <p>All pets are cared for by experienced and pet-loving professionals.</p>
        </div>
    </div>
</section>

<section class = "team">
    <h2>Our Team</h2>
    <ul class="team-list">
      <?php foreach ($pet_trainers as $pet_trainer): ?>
          <li class="team-member">
              <?php
                  $ap = $pet_trainer['profilePhoto'] ?? '';
                  $aPath = "img/users/$ap";
                  if (empty($ap) || !file_exists($aPath)) $aPath = "img/users/user-avatar.png";
              ?>
              <img src="<?= htmlspecialchars($aPath) ?>" alt="<?= htmlspecialchars($pet_trainer['name']) ?>">
              <h3><?= htmlspecialchars($pet_trainer['name']) ?></h3>
              <p><?= htmlspecialchars($pet_trainer['bio']) ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
</section>

<section class="cta">
        <h2>Ready to Start Your Fitness Journey?</h2>
        <p>Join hundreds of members who have already transformed their lives!</p>
        <a href="register.php" class="btn btn-dark">Register Now</a>
        <a href="contact.php" class="btn btn-dark">Contact Us</a>
    </section>

<?php drawFooter(); ?>
