document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('editProfileForm');
    const feedback = document.getElementById('feedback-message');

    setupFilePreview('profilePhoto', 'avatar-preview');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        try {
            const response = await fetch('api/update_profile.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                feedback.className = 'msg-success';
                feedback.style.display = 'block';
                feedback.textContent = result.message;
                window.location.href = 'profile.php';
            } else {
                feedback.className = 'msg-error';
                feedback.textContent = result.message;
            }
        } catch (error) {
            feedback.className = 'msg-error';
            feedback.style.display = 'block';
            feedback.textContent = 'Error connecting to the server.';
            console.error('Error:', error);
        }
    });
});
