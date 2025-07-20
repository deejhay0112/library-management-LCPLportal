// Confirm before deleting a book
function confirmDeletion() {
    return confirm('Are you sure you want to delete this book?');
}

// Placeholder for form submissions (e.g., add/edit book)
document.getElementById('addBookForm')?.addEventListener('submit', function(event) {
    event.preventDefault();
    alert('Book added successfully!');
    window.location.href = 'index.html'; // Redirect to the main list
});

document.getElementById('editBookForm')?.addEventListener('submit', function(event) {
    event.preventDefault();
    alert('Book updated successfully!');
    window.location.href = 'index.html'; // Redirect to the main list
});
