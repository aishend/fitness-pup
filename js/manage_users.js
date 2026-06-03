function openUserModal(action, id = '', name = '', username = '', email = '', role = 'member') {
    document.getElementById('modal-action').value = action;
    document.getElementById('modal-user-id').value = id;
    document.getElementById('modal-name').value = name;
    document.getElementById('modal-username').value = username;
    document.getElementById('modal-email').value = email;
    document.getElementById('modal-role').value = role;

    const titleElement = document.getElementById('modal-title');
    const passwordInput = document.getElementById('modal-password');

    if (action === 'create') {
        titleElement.textContent = 'Create New User';
        passwordInput.required = true;
        passwordInput.placeholder = '';
    } else {
        titleElement.textContent = 'Edit User';
        passwordInput.required = false;
        passwordInput.placeholder = 'Leave blank to keep current';
    }

    openModal('userModal');
}

function closeUserModal() { closeModal('userModal'); }

function openDeleteModal(id, name) {
    document.getElementById('delete-user-name').textContent = name;
    document.getElementById('confirm-delete-btn').onclick = () => {
        document.getElementById('delete-form-' + id).submit();
    };
    openModal('deleteModal');
}

function closeDeleteModal() { closeModal('deleteModal'); }
