<?php
session_start();
include('templates/common.php');
include('database/connection.php');
include('database/plans.php');

$dbh = getDatabaseConnection();
$plans = getPlansForDisplay($dbh);

drawHeader('pricing.css');
?>

<main>
    <section class="pricing-hero">
        <h1>Our Plans</h1>
        <p>Choose the perfect plan for you and your furry friends!</p>
    </section>

    <section class="pricing-container">

        <?php foreach ($plans as $plan): ?>
            <div class="pricing-card <?php echo $plan['is_featured'] ? 'featured' : ''; ?>">
                <?php if ($plan['is_featured']): ?>
                    <span class="badge">Most Popular</span>
                <?php endif; ?>

                <h2><?php echo htmlspecialchars($plan['name']); ?></h2>
                <p class="tagline"><?php echo htmlspecialchars($plan['tagline']); ?></p>

                <div class="price">
                    <span class="amount">€<?php echo number_format($plan['price'], 2); ?></span>
                    <span class="period">/month</span>
                </div>

                <ul class="features">
                    <?php foreach ($plan['features'] as $feature): ?>
                        <li><?php echo htmlspecialchars($feature); ?></li>
                    <?php endforeach; ?>
                </ul>

                <a href="payments.php?planID=<?php echo $plan['planID']; ?>" class="btn btn-primary">Get Started</a>
            </div>
        <?php endforeach; ?>

    </section>

    <section class="faq">
        <h2>Questions?</h2>

        <div class="faq-item">
            <h3>Can I change plans anytime?</h3>
            <p>Yes! You can upgrade or downgrade your plan at any time. Changes take effect on your next billing cycle.</p>
        </div>

        <div class="faq-item">
            <h3>What's included in pet care?</h3>
            <p>Our pet care includes a safe play area, supervision, and treats. Perfect while you work out!</p>
        </div>

        <div class="faq-item">
            <h3>Do I get a free trial?</h3>
            <p>Yes! All new members get 7 days free to explore our facilities and classes.</p>
        </div>

        <div class="faq-item">
            <h3>Can I pause my membership?</h3>
            <p>Absolutely! You can pause for up to 3 months if you need a break.</p>
        </div>
    </section>

</main>

<?php
drawFooter();
?>
