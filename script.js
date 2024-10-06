// Function to toggle the navigation menu
function toggleNav() {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('active');
}

// Add event listener to toggle button
document.querySelector('.toggle-button').addEventListener('click', toggleNav);