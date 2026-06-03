function openPetSessionModal(action, id = '', title = '', roomId = '', trainerId = '', capacity = '', date = '', startTime = '', endTime = '') {
    document.getElementById('modal-action').value = action;
    document.getElementById('modal-session-id').value = id;
    document.getElementById('modal-session-title').value = title;
    document.getElementById('modal-session-room').value = roomId;
    document.getElementById('modal-session-trainer').value = trainerId;
    document.getElementById('modal-session-capacity').value = capacity;
    document.getElementById('modal-session-date').value = date;
    document.getElementById('modal-session-start').value = startTime;
    document.getElementById('modal-session-end').value = endTime;

    document.getElementById('pet-session-modal-title').textContent = action === 'create' ? 'Create New Session' : 'Edit Session';

    openModal('petSessionModal');
}

function closePetSessionModal() { closeModal('petSessionModal'); }

function openDeletePetSessionModal(id, title) {
    document.getElementById('delete-session-title').textContent = title;
    document.getElementById('delete-session-id').value = id;
    document.getElementById('confirm-delete-session-btn').onclick = () => {
        document.getElementById('delete-session-form').submit();
    };
    openModal('deletePetSessionModal');
}

function closeDeletePetSessionModal() { closeModal('deletePetSessionModal'); }

function openPetBookingModal(btn) {
    const sessionId = btn.dataset.sessionId;
    const capacity = parseInt(btn.dataset.capacity);
    const booked = parseInt(btn.dataset.booked);
    const enrolled = JSON.parse(btn.dataset.enrolled);
    const modal = document.getElementById('petBookingModal');
    const myPets = modal ? JSON.parse(modal.dataset.pets || '[]') : [];

    document.getElementById('modal-session-id-book').value = sessionId;
    const list = document.getElementById('modal-pets-list');
    list.innerHTML = '';

    if (myPets.length === 0) {
        list.innerHTML = '<p>You have no pets registered. <a href="register_pet.php">Register one first.</a></p>';
        document.getElementById('modal-confirm-btn').style.display = 'none';
    } else {
        document.getElementById('modal-confirm-btn').style.display = '';
        myPets.forEach(pet => {
            const isEnrolled = enrolled.includes(pet.petID);
            const remaining = capacity - booked;

            const label = document.createElement('label');
            label.className = 'modal-pet-item selectable';

            const cb = document.createElement('input');
            cb.type = 'checkbox';
            cb.name = 'selected_pets[]';
            cb.value = pet.petID;
            cb.checked = isEnrolled;
            cb.disabled = !isEnrolled && remaining <= 0;
            cb.addEventListener('change', () => updatePetModal(capacity, booked, enrolled));

            const nameSpan = document.createElement('span');
            nameSpan.className = 'pet-name';
            nameSpan.textContent = '🐶 ' + pet.name;

            const breedSpan = document.createElement('span');
            breedSpan.className = 'pet-breed';
            breedSpan.textContent = pet.breed;

            label.appendChild(cb);
            label.appendChild(nameSpan);
            label.appendChild(breedSpan);
            list.appendChild(label);
        });
    }

    updatePetModal(capacity, booked, enrolled);
    openModal('petBookingModal');
}

function updatePetModal(capacity, booked, enrolled) {
    const checkboxes = document.querySelectorAll('#modal-pets-list input[type="checkbox"]');
    let newlyChecked = 0;
    checkboxes.forEach(cb => {
        if (cb.checked && !enrolled.includes(parseInt(cb.value))) newlyChecked++;
    });

    const remaining = capacity - booked - newlyChecked;
    document.getElementById('modal-spots-info').textContent = remaining + ' spot(s) remaining';

    checkboxes.forEach(cb => {
        if (!enrolled.includes(parseInt(cb.value)) && !cb.checked) {
            cb.disabled = remaining <= 0;
        }
    });
}

function closePetBookingModal() { closeModal('petBookingModal'); }

// --- AJAX schedule loading ---

const petFilterForm  = document.getElementById('pet-filter-form');
const petScheduleGrid = document.getElementById('pet-schedule-grid');
let currentPetDate   = petScheduleGrid ? petScheduleGrid.dataset.currentDate : '';

function getPetFilterValues() {
    if (!petFilterForm) return { roomID: '', trainerID: '' };
    const roomSel    = petFilterForm.querySelector('[name="pet_roomID"]');
    const trainerSel = petFilterForm.querySelector('[name="trainerID"]');
    return {
        roomID:    roomSel    ? roomSel.value    : '',
        trainerID: trainerSel ? trainerSel.value : '',
    };
}

function updateWeekNav(prevDate, nextDate, weekDisplay, weekNumber) {
    const nav = document.querySelector('.schedule-navigation');
    if (!nav) return;
    const filters  = getPetFilterValues();
    const paramStr = (filters.roomID    ? '&pet_roomID=' + encodeURIComponent(filters.roomID)    : '')
                   + (filters.trainerID ? '&trainerID='  + encodeURIComponent(filters.trainerID) : '');
    const links    = nav.querySelectorAll('.nav-btn');
    if (links[0]) links[0].href = 'pet_rooms.php?date=' + prevDate + paramStr;
    if (links[1]) links[1].href = 'pet_rooms.php?date=' + nextDate + paramStr;
    const h2    = nav.querySelector('.current-week h2');
    const weekP = nav.querySelector('.current-week p');
    if (h2)    h2.textContent    = weekDisplay;
    if (weekP) weekP.textContent = 'Week ' + weekNumber;
}

function loadPetSchedule(date, roomID, trainerID) {
    if (!petScheduleGrid) return;
    petScheduleGrid.innerHTML = '<p class="no-classes-msg" style="padding:2rem;text-align:center;">Loading...</p>';

    const params = new URLSearchParams({ date: date });
    if (roomID)    params.set('pet_roomID', roomID);
    if (trainerID) params.set('trainerID',  trainerID);

    fetch('api/pet_schedule.php?' + params.toString(), { credentials: 'same-origin' })
        .then(function (r) {
            if (!r.ok) throw new Error('Request failed');
            return r.json();
        })
        .then(function (data) {
            currentPetDate = date;
            petScheduleGrid.innerHTML = data.grid;
            updateWeekNav(data.prevDate, data.nextDate, data.weekDisplay, data.weekNumber);
        })
        .catch(function () {
            petScheduleGrid.innerHTML = '<p class="no-classes-msg" style="padding:2rem;text-align:center;">Failed to load sessions. Please try again.</p>';
        });
}

// Intercept filter form submit
if (petFilterForm) {
    petFilterForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const filters = getPetFilterValues();
        loadPetSchedule(currentPetDate, filters.roomID, filters.trainerID);
    });
}

// Intercept week nav link clicks
document.addEventListener('click', function (e) {
    const link = e.target.closest('.schedule-navigation .nav-btn');
    if (!link) return;
    e.preventDefault();
    const url     = new URL(link.href, location.href);
    const newDate = url.searchParams.get('date') || currentPetDate;
    const filters = getPetFilterValues();
    loadPetSchedule(newDate, filters.roomID, filters.trainerID);
});

// Initial load on page ready
if (petScheduleGrid) {
    const filters = getPetFilterValues();
    loadPetSchedule(currentPetDate, filters.roomID, filters.trainerID);
}
