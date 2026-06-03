document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerPetForm');
    const feedback = document.getElementById('feedback-message');

    setupFilePreview('petPhoto', 'pet-photo-preview');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        try {
            const response = await fetch('api/register_pet.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                window.location.href = result.redirect;
            } else {
                feedback.className = 'alert alert-error';
                feedback.style.display = 'block';
                feedback.textContent = result.message;
            }
        } catch (error) {
            feedback.className = 'alert alert-error';
            feedback.style.display = 'block';
            feedback.textContent = 'Error connecting to the server.';
        }
    });
});
