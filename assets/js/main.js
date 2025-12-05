document.addEventListener('DOMContentLoaded', function() {
    const profileDropdown = document.getElementById('profileDropdown');
    
    if (profileDropdown) {
        profileDropdown.addEventListener('click', function(e) {
            this.querySelector('.dropdown-menu').classList.toggle('show');
            e.stopPropagation();
        });

        document.addEventListener('click', function(e) {
            if (!profileDropdown.contains(e.target)) {
                profileDropdown.querySelector('.dropdown-menu').classList.remove('show');
            }
        });
    }
});