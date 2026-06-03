<?php 
session_start();
include('templates/common.php');
include('database/connection.php');

if (!isset($_SESSION['userID'])) {
    $_SESSION['error_message'] = "Please log in to purchase a membership.";
    header("Location: login.php");
    exit;
} 

$planID = isset($_GET['planID']) ? intval($_GET['planID']) : 0;

include('database/plans.php');

$planID = isset($_GET['planID']) ? intval($_GET['planID']) : 0;

try {
    $db = getDatabaseConnection();
    
    $allPlans = getPlansForDisplay($db);
    $plan = null;

    foreach ($allPlans as $p) {
        if ($p['planID'] === $planID) {
            $plan = $p;
            break;
        }
    }

    if (!$plan) {
        header("Location: pricing.php");
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

drawHeader('payments.css'); 
?>

<main class="payment-container">
    <div class="payment-wrapper">
        
        <section class="order-summary">
            <h2>Order Summary</h2>
            <div class="summary-card">
                <div class="plan-details">
                    <h3>Fitness Pup - <?php echo htmlspecialchars($plan['name']); ?></h3>
                    <p class="tagline"><?php echo htmlspecialchars($plan['tagline'] ?? 'Premium Access'); ?></p>
                </div>
                <hr>
                <div class="price-row">
                    <span>Monthly Subscription</span>
                    <span>€<?php echo number_format($plan['price'], 2); ?></span>
                </div>
                <div class="price-row total">
                    <span>Total Due Now</span>
                    <strong>€<?php echo number_format($plan['price'], 2); ?></strong>
                </div>
            </div>
            <p class="secure-text"><i class="icon">🔒</i> Secure Mockup Encrypted Checkout</p>
        </section>

        <section class="payment-form-section">
            <h2>Payment Details</h2>
            <div id="payment-feedback"></div>

            <form id="mockupPaymentForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="planID" value="<?php echo $plan['planID']; ?>">

                <div class="form-group">
                    <label for="card_name">Cardholder Name</label>
                    <input type="text" id="card_name" name="card_name" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label for="card_number">Card Number</label>
                    <input type="text" id="card_number" placeholder="4532 •••• •••• 8824" maxlength="19" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="card_expiry">Expiration Date</label>
                        <input type="text" id="card_expiry" placeholder="MM/YY" maxlength="5" required>
                    </div>
                    <div class="form-group">
                        <label for="card_cvv">CVV</label>
                        <input type="text" id="card_cvv" placeholder="123" maxlength="3" required>
                    </div>
                </div>

                <button type="submit" class="pay-btn">Authorize Mock Payment</button>
                <a href="pricing.php" class="back-link">Cancel and go back</a>
            </form>
        </section>

    </div>
</main>

<script src="js/payments.js"></script>

<?php 
drawFooter(); 
?>

