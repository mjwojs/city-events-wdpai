function toggleProfileMenu() {
    const profileDropdown = document.getElementById('profile-dropdown');
    if (profileDropdown.style.display === 'block') {
        profileDropdown.style.opacity = 0;
        setTimeout(() => {
            profileDropdown.style.display = 'none';
        }, 300);
    } else {
        profileDropdown.style.display = 'block';
        setTimeout(() => {
            profileDropdown.style.opacity = 1;
        }, 10);
    }
}

document.addEventListener('click', function(event) {
    const profileDropdown = document.getElementById('profile-dropdown');
    const profilePicture = document.querySelector('.profile-picture-thumb');

    if (profilePicture && profileDropdown) {
        if (event.target !== profilePicture && !profileDropdown.contains(event.target)) {
            profileDropdown.style.opacity = 0;
            setTimeout(() => {
                profileDropdown.style.display = 'none';
            }, 300);
        }
    }
});
