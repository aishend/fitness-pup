document.getElementById('newsletter-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const feedback = document.getElementById('newsletter-feedback');
    const data = new FormData(form);
    try {
        const res = await fetch('api/newsletter_subscribe.php', { method: 'POST', body: data });
        const json = await res.json();
        feedback.textContent = json.message;
        feedback.className = 'newsletter-feedback ' + json.status;
    } catch {
        feedback.textContent = 'Something went wrong. Please try again.';
        feedback.className = 'newsletter-feedback error';
    }
});
