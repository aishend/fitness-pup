function openForgotModal() {
    document.getElementById('forgot-form-view').style.display = '';
    document.getElementById('forgot-success-view').style.display = 'none';
    document.getElementById('forgot-email').value = '';
    document.getElementById('forgot-email-confirm').value = '';
    document.getElementById('forgot-error').style.display = 'none';
    document.getElementById('forgotModal').classList.add('visible');
}

function closeForgotModal() {
    document.getElementById('forgotModal').classList.remove('visible');
}

function handleForgotSubmit(e) {
    e.preventDefault();
    const email   = document.getElementById('forgot-email').value.trim();
    const confirm = document.getElementById('forgot-email-confirm').value.trim();
    const errorEl = document.getElementById('forgot-error');

    if (email !== confirm) {
        errorEl.textContent = 'Email addresses do not match.';
        errorEl.style.display = '';
        return;
    }

    errorEl.style.display = 'none';

    const csrfToken = document.querySelector('#forgotModal input[name="csrf_token"]').value;
    const body = new URLSearchParams({ csrf_token: csrfToken, email });

    fetch('actions/action_forgot_password.php', { method: 'POST', body })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                document.getElementById('forgot-sent-email').textContent = email;
                document.getElementById('forgot-form-view').style.display = 'none';
                document.getElementById('forgot-success-view').style.display = '';
            } else {
                errorEl.textContent = data.error || 'Something went wrong.';
                errorEl.style.display = '';
            }
        })
        .catch(() => {
            errorEl.textContent = 'Could not reach the server. Please try again.';
            errorEl.style.display = '';
        });
}
