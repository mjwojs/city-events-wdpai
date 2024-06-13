function toggleProfileMenu() {
    const profileDropdown = document.getElementById('profile-dropdown');
    profileDropdown.style.display = profileDropdown.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener('click', function(event) {
    const profileDropdown = document.getElementById('profile-dropdown');
    const profilePicture = document.querySelector('.profile-picture-thumb');

    if (profilePicture && profileDropdown) {
        if (event.target !== profilePicture && !profileDropdown.contains(event.target)) {
            profileDropdown.style.display = 'none';
        }
    }
});
