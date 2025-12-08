// DARK MODE
function toggleDarkMode() {
    const body = document.body;
    const sidebar = document.querySelector('.sidebar');
    const content = document.querySelector('.content');
    const icon = document.getElementById('darkBtn').querySelector('i');

    body.classList.toggle('dark-mode');
    sidebar.classList.toggle('dark-mode');
    content.classList.toggle('dark-mode');

    if (body.classList.contains('dark-mode')) {
        icon.classList.replace('bi-moon', 'bi-brightness-high');
    } else {
        icon.classList.replace('bi-brightness-high', 'bi-moon');
    }

    localStorage.setItem('darkMode', body.classList.contains('dark-mode') ? '1' : '0');
}

document.addEventListener("DOMContentLoaded", () => {
    if (localStorage.getItem('darkMode') === '1') {
        toggleDarkMode();
    }
});
