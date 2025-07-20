// edit-books.js

function openEditModal() {
    document.getElementById('editBookModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editBookModal').style.display = 'none';
}

// Event listener to close the modal when clicking outside of the modal content
window.onclick = function(event) {
    if (event.target === document.getElementById('editBookModal')) {
        closeEditModal();
    }
}
