function switchGroup(group) {
    document.getElementById('group-gym').style.display = group === 'gym' ? 'block' : 'none';
    document.getElementById('group-pup').style.display = group === 'pup' ? 'block' : 'none';
    document.getElementById('btn-gym').classList.toggle('active', group === 'gym');
    document.getElementById('btn-pup').classList.toggle('active', group === 'pup');
}