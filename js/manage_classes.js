function openClassModal(action, id = '', title = '', trainerId = '', type = '', capacity = '', date = '', startTime = '', endTime = '', imageFilename = '') {
    document.getElementById('modal-action').value = action;
    document.getElementById('modal-class-id').value = id;
    document.getElementById('modal-title').value = title;
    document.getElementById('modal-trainer').value = trainerId;
    document.getElementById('modal-type').value = type;
    document.getElementById('modal-capacity').value = capacity;
    document.getElementById('modal-date').value = date;
    document.getElementById('modal-start-time').value = startTime;
    document.getElementById('modal-end-time').value = endTime;

    document.getElementById('modal-current-class-image').value = imageFilename;
    const preview = document.getElementById('modal-image-preview');
    const img = document.getElementById('modal-current-image');
    if (imageFilename) {
        img.src = 'img/classes/' + imageFilename;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
    const fileInput = document.querySelector('#classModal input[name="class_image"]');
    if (fileInput) fileInput.value = '';

    document.getElementById('class-modal-title').textContent = action === 'create' ? 'Create New Class' : 'Edit Class';

    openModal('classModal');
}

function closeClassModal() { closeModal('classModal'); }

function openDeleteClassModal(id, title) {
    document.getElementById('delete-class-title').textContent = title;
    document.getElementById('confirm-delete-class-btn').onclick = () => {
        document.getElementById('delete-class-form-' + id).submit();
    };
    openModal('classDeleteModal');
}

function closeDeleteClassModal() { closeModal('classDeleteModal'); }

function closePetPrompt() { closeModal('petPromptModal'); }


// ajax para editar a aula sem ter que dar relaoad 
var classModalForm = document.querySelector('#classModal form');
if (classModalForm) {
    classModalForm.addEventListener('submit', function (e) {
        e.preventDefault();
        fetch('actions/admin/action_class.php', {
            method: 'POST',
            body: new FormData(classModalForm)
        }).then(function () {
            closeClassModal();
            window.location.reload();
        }).catch(function () {
            alert('Something went wrong. Please try again.');
        });
    });
}
