function switchGroup(group) {
    document.getElementById('group-gym').style.display = group === 'gym' ? 'block' : 'none';
    document.getElementById('group-pup').style.display = group === 'pup' ? 'block' : 'none';
    document.getElementById('btn-gym').classList.toggle('active', group === 'gym');
    document.getElementById('btn-pup').classList.toggle('active', group === 'pup');
    applyFilter();
}

function applyFilter() {
    const availableOnly = document.getElementById('available-only').checked;
    const activeGroup = document.getElementById('group-gym').style.display !== 'none' ? 'group-gym' : 'group-pup';
    const cards = document.querySelectorAll('#' + activeGroup + ' .equipment-card');
    cards.forEach(card => {
        card.style.display = (!availableOnly || card.dataset.status === 'available') ? 'flex' : 'none';
    });
}

function openEquipmentModal(action, id = '', name = '', description = '', categoryId = '', status = 'available') {
    document.getElementById('modal-action').value = action;
    document.getElementById('modal-id').value = id;
    document.getElementById('modal-name').value = name;
    document.getElementById('modal-description').value = description;
    document.getElementById('modal-category').value = categoryId;
    document.getElementById('modal-status').value = status;

    document.getElementById('modal-title').textContent = action === 'create' ? 'Add New Equipment' : 'Edit Equipment';

    openModal('equipmentModal');
}

function closeEquipmentModal() { closeModal('equipmentModal'); }

function openDeleteEquipmentModal(id, name) {
    document.getElementById('delete-equipment-name').textContent = name;
    document.getElementById('delete-equipment-id').value = id;
    openModal('equipmentDeleteModal');
}

function closeDeleteEquipmentModal() { closeModal('equipmentDeleteModal'); }
