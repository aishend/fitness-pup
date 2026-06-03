function openModal(id) {
    document.getElementById(id).classList.add('visible');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('visible');
}

function handleOverlayClick(event, modalId) {
    if (event.target.id === modalId) closeModal(modalId);
}

function setupFilePreview(inputId, previewId, afterChange) {
    document.getElementById(inputId).addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => document.getElementById(previewId).src = e.target.result;
        reader.readAsDataURL(file);
        if (afterChange) afterChange(file);
    });
}
