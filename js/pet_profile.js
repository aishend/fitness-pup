function toggleEdit(show) {
    document.getElementById('pet-view').style.display = show ? 'none' : '';
    document.getElementById('pet-edit').style.display = show ? '' : 'none';
}

setupFilePreview('petPhoto', 'pet-photo-display', file => {
    document.getElementById('photo-chosen').textContent = file.name;
});

document.getElementById('editPetForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const feedback = document.getElementById('pet-feedback');
    feedback.className = '';
    feedback.style.display = 'none';

    try {
        const res    = await fetch('api/update_pet.php', { method: 'POST', body: formData });
        const result = await res.json();

        if (result.success) {
            document.getElementById('display-name').textContent  = formData.get('name');
            document.getElementById('display-breed').textContent = formData.get('breed');
            document.getElementById('display-age').textContent   = formData.get('age') + ' years old';

            const vacc = document.getElementById('display-vacc');
            const isVacc = !!formData.get('vaccinated');
            vacc.textContent = isVacc ? '✓ Vaccinated' : '✗ Not Vaccinated';
            vacc.className   = 'vacc-badge ' + (isVacc ? 'vacc-yes' : 'vacc-no');

            if (result.photo) {
                document.getElementById('pet-photo-display').src = './img/pets/' + result.photo;
            }
            toggleEdit(false);
        } else {
            feedback.textContent = result.message;
            feedback.className   = 'msg-error';
        }
    } catch {
        feedback.textContent = 'Error connecting to the server.';
        feedback.className   = 'msg-error';
    }
});
