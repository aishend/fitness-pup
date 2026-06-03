<?php
  session_start();
  require_once 'templates/common.php';

  drawHeader('login_register.css');
?>

<main>
    <section class="register-section">
        <div class="register-box">
            <div class="register-header">
                <h2>Create an Account</h2>
                <img src="./img/logo.png" alt="Fitness Pup Logo" class="register-logo">
            </div>

            <?php drawSessionMessages(); ?>

            <form action="actions/register_action.php" method="POST" class="access-form">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="input-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="John Doe" required>
                </div>

                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Joao_123" required>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="email@example.com" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" class="btn btn-dark access-btn">Register</button>
            </form>

            <p class="access-link">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </section>
</main>

<?php drawFooter(); ?>