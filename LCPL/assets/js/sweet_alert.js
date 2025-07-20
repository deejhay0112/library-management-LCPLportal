// Book Modal Submission with SweetAlert confirmation
document.getElementById('bookModal').addEventListener('submit', function(event) {
    event.preventDefault(); // prevent form submission for validation

    // Perform your validation logic here
    let isValid = true; // assume it's true for example

    if (isValid) {
        // Trigger SweetAlert for confirmation
        Swal.fire({
            title: 'Confirm Submission',
            text: 'Are you sure you want to submit this form?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                event.target.submit();
            }
        });
    }
});

// Edit Book Modal Submission with SweetAlert feedback
document.getElementById('editBookForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    const formData = new FormData(this); // Get the form data

    // Send form data via AJAX to the edit_book.php script
    fetch('edit_book.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Parse the JSON response from PHP
    .then(data => {
        if (data.success) {
            // Trigger SweetAlert success message
            Swal.fire({
                title: 'Success!',
                text: data.message, // This message comes from the server
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Optionally reload the page or close the modal after success
                document.getElementById("editBookModal").style.display = "none"; // Close the modal
                window.location.href = 'books.php'; // Redirect to books page
            });
        } else {
            // Show error SweetAlert if the update failed
            Swal.fire({
                title: 'Error!',
                text: data.error || 'An error occurred while updating the book.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        // Handle network errors or issues during the request
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while submitting the form.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        console.error('Error during form submission:', error);
    });
});

// Utility functions

// Refresh the page
function refreshPage() {
    window.location.reload();
}

// Validate input for numbers and slashes only
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 47) {
        return false;
    }
    return true;
}
