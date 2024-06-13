document.addEventListener('DOMContentLoaded', (event) => {
    const logoutLink = document.querySelector('nav a[href="/logout"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to log out?')) {
                e.preventDefault();
            }
        });
    }
});
