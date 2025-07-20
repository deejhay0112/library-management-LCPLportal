// Show Navbar Toggle Function
const showNavbar = (toggleId, navId, bodyId, headerId) => {
    const toggle = document.getElementById(toggleId),
        nav = document.getElementById(navId),
        bodypd = document.getElementById(bodyId),
        headerpd = document.getElementById(headerId);

    // Ensure all elements exist
    if (toggle && nav && bodypd && headerpd) {
        toggle.addEventListener('click', () => {
            // Toggle the 'show' class on the navbar
            nav.classList.toggle('show');
            console.log('Navbar toggled:', nav.classList.contains('show')); // Debugging log

            // Change icon (optional, remove if unnecessary)
            toggle.classList.toggle('bx-x');

            // Toggle padding for body and header
            bodypd.classList.toggle('body-pd');
            headerpd.classList.toggle('body-pd');
        });
    } else {
        console.error("Error: One or more elements are missing.");
    }
};

// Initialize Navbar Toggle
showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

// Link Active Class Toggle
const linkColor = document.querySelectorAll('.nav__link');
function colorLink() {
    linkColor.forEach(l => l.classList.remove('active'));
    this.classList.add('active');
}
linkColor.forEach(l => l.addEventListener('click', colorLink));

document.addEventListener('DOMContentLoaded', function () {
    // Edit Button Functionality
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default link behavior
            const row = this.closest('tr'); // Get the closest row element
            const rowData = {
                id: row.cells[0].textContent,
                name: row.cells[1].textContent,
                age: row.cells[2].textContent,
                email: row.cells[3].textContent
            };
            console.log('Edit:', rowData); // Replace this with your edit logic
            alert(`Editing user: ${rowData.name}`); // Use backticks for template literals
        });
    });

    // Delete Button Functionality
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default link behavior
            const row = this.closest('tr'); // Get the closest row element
            const rowData = {
                id: row.cells[0].textContent,
                name: row.cells[1].textContent,
                age: row.cells[2].textContent,
                email: row.cells[3].textContent
            };
            console.log('Delete:', rowData); // Replace this with your delete logic
            if (confirm(`Are you sure you want to delete user: ${rowData.name}?`)) { // Use backticks for template literals
                row.remove(); // Remove the row from the table
            }
        });
    });

    // Page Navigation and Content Display
    const navLinks = document.querySelectorAll('.nav__link');
    const pageContents = document.querySelectorAll('.page-content');

    function showPage(pageId) {
        // Hide all pages
        pageContents.forEach(page => page.classList.remove('active'));

        // Show the selected page
        const activePage = document.getElementById(pageId);
        if (activePage) {
            activePage.classList.add('active');
        }
    }

    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            // Remove 'active' class from all links
            navLinks.forEach(link => link.classList.remove('active'));

            // Add 'active' class to the clicked link
            this.classList.add('active');

            // Get the page ID from the data-page attribute
            const pageId = this.getAttribute('data-page');
            if (pageId) {
                // Show the selected page
                showPage(pageId);
            }
        });
    });

    // Initially show the first page if it exists
    if (navLinks.length > 0 && navLinks[0].getAttribute('data-page')) {
        const initialPage = navLinks[0].getAttribute('data-page');
        showPage(initialPage);
    }
});
