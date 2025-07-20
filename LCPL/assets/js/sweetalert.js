// SweetAlert2 - Handle cancel button for Add Book modal
function handleAddBookCancel(modal) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to recover unsaved data!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Cancelled',
                'Your form was reset.',
                'success'
            );
            modal.style.display = "none"; // Close the modal
        }
    });
}

// SweetAlert2 - Handle cancel button for Edit Book modal
function handleEditBookCancel(editModal) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Changes will not be saved!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Cancelled',
                'Your form was reset.',
                'success'
            );
            editModal.style.display = "none"; // Close the modal
        }
    });
}

// SweetAlert2 - Handle delete confirmation
function handleDeleteBook(bookId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to recover this book!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('delete_book.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${bookId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Deleted!',
                        'The book has been deleted.',
                        'success'
                    );
                    document.getElementById(`row-${bookId}`).remove();
                } else {
                    Swal.fire(
                        'Error!',
                        'Failed to delete the book. ' + data.message,
                        'error'
                    );
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
}

// SweetAlert2 - Handle search validation
function handleSearchValidation(searchInput) {
    if (searchInput.trim() === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Please enter a search term!',
        });
        return false;
    }
    return true;
}
