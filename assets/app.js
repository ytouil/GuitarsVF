/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// dropdown.js
function toggleDropdown(dropdownId) {
    let element = document.getElementById(dropdownId);
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
        element.classList.add('block');
    } else {
        element.classList.remove('block');
        element.classList.add('hidden');
    }
}

// Export the function if you need to use it in other modules
export { toggleDropdown };

document.addEventListener('DOMContentLoaded', () => {
    const dropdownButton = document.querySelector('[data-dropdown-toggle="dropdown-user"]');
    if (dropdownButton) {
        dropdownButton.addEventListener('click', () => toggleDropdown('dropdown-user'));
    }
});


