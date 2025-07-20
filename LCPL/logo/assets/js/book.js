// Add Book Modal
var modal = document.getElementById("bookModal");
var openModalBtn = document.getElementById("openModalBtn");
var closeModalBtn = document.getElementById("closeModalBtn");
var span = document.getElementsByClassName("close")[0];

// Open modal
openModalBtn.onclick = function() {
    modal.style.display = "block";
}

// Close modal
closeModalBtn.onclick = function() {
    modal.style.display = "none";
}
span.onclick = function() {
    modal.style.display = "none";
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Edit Book Modal
function openEditModal(bookId) {
    // Fetch the book details via AJAX
    fetch(`fetch_book_details.php?id=${bookId}`)
        .then(response => response.json()) // Parse JSON response
        .then(data => {
            // Populate the modal form fields with the fetched data
            document.getElementById("edit_book_id").value = bookId;
            document.getElementById("edit_no_of_books").value = data['No of Books'];
            document.getElementById("edit_author").value = data['Author_Title'];
            document.getElementById("edit_author_title").value = data['Title'];
            document.getElementById("edit_accession_no").value = data['Accession No'];
            document.getElementById("edit_call_no").value = data['Call No'];
            document.getElementById("edit_quantity").value = data['Quantity'];
            document.getElementById("edit_unit").value = data['Unit'];
            document.getElementById("edit_date_acquired").value = data['Date Acquired'];

            // Display the modal
            document.getElementById("editBookModal").style.display = "block";
        })
        .catch(error => {
            // Handle error during the fetch operation
            Swal.fire({
                title: 'Error!',
                text: 'Failed to fetch book details.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            console.error('Error fetching book details:', error);
        });

        
}



// Close the Edit Book Modal
document.getElementById("closeEditModal").onclick = function() {
    document.getElementById("editBookModal").style.display = "none";
}

document.getElementById("closeEditModalBtn").onclick = function() {
    document.getElementById("editBookModal").style.display = "none";
}

// Delete Book
function deleteRow(bookId) {
    // Show SweetAlert confirmation dialog
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send the AJAX request to delete the book
            fetch('delete_book.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${bookId}` // Send the book ID as part of the request
            })
            .then(response => response.json()) // Parse the JSON response
            .then(data => {
                if (data.success) {
                    // Show success SweetAlert if deletion was successful
                    Swal.fire(
                        'Deleted!',
                        'The book has been deleted.',
                        'success'
                    ).then(() => {
                        // Optionally, remove the row from the table or reload the page
                        document.getElementById(`row-${bookId}`).remove(); // Remove the deleted row
                    });
                } else {
                    // Show error SweetAlert if there was a problem with the deletion
                    Swal.fire(
                        'Error!',
                        data.message || 'An error occurred while deleting the book.',
                        'error'
                    );
                }
            })
            .catch(error => {
                // Handle network errors
                Swal.fire(
                    'Error!',
                    'An error occurred while processing the request.',
                    'error'
                );
                console.error('Error:', error);
            });
        }
    });
}

function searchBooks() {
    const searchInput = document.getElementById('searchInput').value;

    // Make sure searchInput is not empty
    if (searchInput.trim() === '') {
        alert("Please enter a search term.");
        return; // Exit the function if search term is empty
    }

    // Make an AJAX request to search_books.php
    fetch(`search_book.php?search=${encodeURIComponent(searchInput)}`)
        .then(response => response.text())
        .then(data => {
            // Update the table with the fetched data
            const booksTableBody = document.getElementById('booksTablebody');
            booksTableBody.innerHTML = ''; // Clear existing table body
            
            // Populate the table with fetched data
            booksTableBody.innerHTML = data; // Assuming data contains the rows of the table
        })
        .catch(error => console.error('Error fetching data:', error));
}