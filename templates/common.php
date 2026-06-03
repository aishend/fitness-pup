<?php

function drawHeader(string $style = null) {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Fitness Pup</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/components.css">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/footer.css">
        <?php if ($style) { ?>
            <link rel="stylesheet" href="css/<?=$style?>">
        <?php } ?>
        <script src="js/utils.js"></script>
        <script src="js/pagination.js"></script>
    </head>
    <body>
        <header class="header">
            <a href="index.php"><img src="./img/logo.png" alt="Fitness Pup Logo"></a>
            <input type="checkbox" id="menu-toggle" hidden>
            <label for="menu-toggle" class="menu-icon">&#9776;</label>

            <nav id="signup">
                <?php if(isset($_SESSION['userID'])) { ?>
                    <a href="classes.php">Classes</a>
                    <a href="pet_rooms.php">Pet Rooms</a>
                    <a href="pricing.php">Pricing</a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
                        <a href="admin_dashboard.php">Dashboard</a>
                    <?php } ?>
                    <a href="logout.php">Logout</a>
                    <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { ?>
                        <a href="profile.php">Profile</a>
                    <?php } ?>
                    
                <?php } else { ?>
                    <a href="pricing.php">Pricing</a>
                    <a href="about.php">About</a>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php } ?>
                
            </nav>
        </header>
<?php
}

function drawFooter() {
?>
    <footer class="footer">
       <div class="footer-table">
           <div class="footer-col">
               <h2>Fitness Pup</h2>
               <p>Your premier pet-friendly fitness destination for achieving your health goals</p>
           </div>
           <div class="footer-col">
               <h2>Quick Links</h2>
               <ul>
                   <li><a href="index.php">Home</a></li>
                   <?php if(isset($_SESSION['userID'])) { ?>
                       <li><a href="classes.php">Classes</a></li>
                       <li><a href="pet_rooms.php">Pet Rooms</a></li>
                   <?php }  ?>
                   <li><a href="register.php">Register</a></li>
                   <li><a href="login.php">Login</a></li>
                   <li><a href="pricing.php">Pricing</a></li>
                    <li><a href="about.php">About Us</a></li>
               </ul>
           </div>
           <div class="footer-col">
               <h2>For Members</h2>
               <ul>
                   <li><a href="classes.php">Classes</a></li>
                   <li><a href="trainers.php">Trainers</a></li>
                   <li><a href="equipment.php">Equipment</a></li>
                   <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { ?>
                       <li><a href="profile.php">Profile</a></li>
                   <?php } ?>
               </ul>
           </div>
           <div class="footer-col">
               <h2>Contact Us</h2>
               <p>sala b321 feup</p>
               <p>Porto, Portugal</p>
               <p>bringYourDog@fitnesspup.com</p>
               <p>900 000 000</p>
           </div>
       </div>

       <div class="footer-bottom">
           <p>&copy; 2026 Fitness Pup. All rights reserved.</p>
       </div>
    </footer>
    </body>
    </html>
<?php
}

function drawSessionMessages() {
?>
    <?php if (isset($_SESSION['error_message'])) { ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php } ?>

    <?php if (isset($_SESSION['success_message'])) { ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php } ?>
<?php
}
?>