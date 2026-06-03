function openEditProfileModal() {
    document.getElementById('editProfileModal').classList.add('visible');
}

function closeEditProfileModal() {
    document.getElementById('editProfileModal').classList.remove('visible');
}

function handleProfileOverlayClick(event) {
    if (event.target.id === 'editProfileModal') {
        closeEditProfileModal();
    }
}

function openReviewModal(enrollmentID) {
    document.getElementById('review-enrollment-id').value = enrollmentID;
    document.querySelectorAll('#reviewModal input[type="radio"]').forEach(r => r.checked = false);
    document.getElementById('review-comment').value = '';
    document.getElementById('reviewModal').classList.add('visible');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.remove('visible');
}

function handleReviewOverlayClick(event) {
    if (event.target.id === 'reviewModal') {
        closeReviewModal();
    }
}

function openProfileClassModal(classID, name, trainerID, type, capacity, date, startTime, endTime, imageFilename = '') {
    document.getElementById('profile-modal-class-id').value  = classID;
    document.getElementById('profile-modal-title').value     = name;
    document.getElementById('profile-modal-type').value      = type;
    document.getElementById('profile-modal-capacity').value  = capacity;
    document.getElementById('profile-modal-date').value      = date;
    document.getElementById('profile-modal-start').value     = startTime;
    document.getElementById('profile-modal-end').value       = endTime;

    document.getElementById('profile-modal-current-class-image').value = imageFilename;
    const preview = document.getElementById('profile-modal-image-preview');
    const img = document.getElementById('profile-modal-current-image');
    if (imageFilename) {
        img.src = 'img/classes/' + imageFilename;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
    const fileInput = document.querySelector('#profileClassModal input[name="class_image"]');
    if (fileInput) fileInput.value = '';

    document.getElementById('profileClassModal').classList.add('visible');
}

function closeProfileClassModal() {
    document.getElementById('profileClassModal').classList.remove('visible');
}

function handleProfileClassOverlayClick(event) {
    if (event.target.id === 'profileClassModal') {
        closeProfileClassModal();
    }
}