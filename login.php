<?php
  session_start();
  require_once 'templates/common.php';

  drawHeader('login_register.css');
?>

<main>
    <section class="login-section">
        <div class="login-box">
            <div class="login-header">
                <h2>Welcome</h2>
                <img src="./img/logo.png" alt="Fitness Pup Logo" class="login-logo">
            </div>

            <?php drawSessionMessages(); ?>

            <form action="actions/login_action.php" method="POST" class="access-form">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="input-group">
                    <label for="login_input">Email or Username</label>
                    <input type="text" id="login_input" name="login_input" placeholder="email@example.com or username" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" class="btn btn-dark access-btn">Login</button>
            </form>

            <p class="access-link">Don't have an account? <a href="register.php">Register here</a></p>
            <p class="access-link"><a href="#" onclick="openForgotModal(); return false;">Forgot your password?</a></p>
        </div>
    </section>
</main>

<div id="forgotModal" class="modal-overlay" onclick="if(event.target.id==='forgotModal') closeForgotModal()">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Reset Password</h2>
            <button type="button" class="btn-text-only" style="font-size:1.5rem;" onclick="closeForgotModal()">&times;</button>
        </div>

        <div id="forgot-form-view">
            <form onsubmit="handleForgotSubmit(event)">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <div class="modal-body">
                    <p style="margin-bottom:1rem;color:#555;">Enter your email address and we'll send you a link to reset your password.</p>
                    <div class="input-group">
                        <label for="forgot-email">Email</label>
                        <input type="email" id="forgot-email" placeholder="email@example.com" required>
                    </div>
                    <div class="input-group">
                        <label for="forgot-email-confirm">Confirm Email</label>
                        <input type="email" id="forgot-email-confirm" placeholder="email@example.com" required>
                    </div>
                    <p id="forgot-error" style="color:var(--color-danger,#c0392b);display:none;margin-top:0.5rem;"></p>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                    <button type="button" class="btn btn-secondary" onclick="closeForgotModal()">Cancel</button>
                </div>
            </form>
        </div>

        <div id="forgot-success-view" style="display:none;">
            <div class="modal-body" style="text-align:center;padding:2rem 1rem;">
                <p style="font-size:1.1rem;">A confirmation email has been sent to <strong id="forgot-sent-email"></strong>.</p>
                <p style="color:#555;margin-top:0.5rem;">Please check your inbox and follow the instructions to reset your password.</p>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-primary" onclick="closeForgotModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="js/login.js"></script>

<?php drawFooter(); ?>