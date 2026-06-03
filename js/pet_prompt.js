document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('petPromptModal');
    if (modal) {
        modal.classList.add('visible');
    }
});

function closePetPrompt() {
    const modal = document.getElementById('petPromptModal');
    if (modal) {
        modal.classList.remove('visible');
    }
}