document.getElementById('mockupPaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = this.querySelector('.pay-btn');
    const feedback = document.getElementById('payment-feedback');

    btn.disabled = true;
    btn.textContent = 'Processing Secure Payment...';

    setTimeout(() => {
        const formData = new FormData(this);

        fetch('api/process_payment.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                feedback.className = 'msg-success';
                feedback.style.display = 'block';
                feedback.textContent = 'Payment approved! Redirecting to your dashboard...';

                setTimeout(() => {
                    window.location.href = 'profile.php';
                }, 2000);
            } else {
                feedback.className = 'msg-error';
                feedback.style.display = 'block';
                feedback.textContent = data.message;
                btn.disabled = false;
                btn.textContent = 'Authorize Mock Payment';
            }
        })
        .catch(() => {
            feedback.className = 'msg-error';
            feedback.style.display = 'block';
            feedback.textContent = 'Connection error.';
            btn.disabled = false;
            btn.textContent = 'Authorize Mock Payment';
        });
    }, 1500);
});
