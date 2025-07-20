// Main function to handle book search
function searchBooks() {
    // Get the input element and its value
    const inputElement = document.getElementById("borrow_book_name");
    if (!inputElement) {
        console.error("Element with ID 'borrow_book_name' not found.");
        return;
    }

    const borrowedBookSearchTerm = inputElement.value.trim();

    // Get the search results container
    const borrowSearchResults = document.getElementById("borrowSearchResults");
    if (!borrowSearchResults) {
        console.error("Element with ID 'borrowSearchResults' not found.");
        return;
    }

    // Perform search if the input is longer than 2 characters
    if (borrowedBookSearchTerm.length > 2) {
        fetchBooks(borrowedBookSearchTerm, borrowSearchResults);
    } else {
        borrowSearchResults.style.display = "none"; // Hide if input is too short
        borrowSearchResults.innerHTML = ""; // Clear results
    }
}

// Function to fetch books based on the search term
function fetchBooks(searchTerm, searchResults) {
    // Fetch books from the server
    fetch(`fetch_borrowbooks.php?search=${encodeURIComponent(searchTerm)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(books => {
            // Clear previous results
            searchResults.innerHTML = "";

            if (books.length > 0) {
                searchResults.style.display = "block"; // Show the results container
                books.forEach(book => {
                    // Create a result item for each book
                    const bookItem = document.createElement("div");
                    bookItem.textContent = `${book.Title} by ${book.Author_Title} (Accession No: ${book['Accession No']})`;

                    // Attach a click event to select the book
                    bookItem.onclick = () => selectBook(book, searchResults);

                    searchResults.appendChild(bookItem);
                });
            } else {
                searchResults.style.display = "none"; // Hide if no books found
            }
        })
        .catch(error => {
            console.error("Error fetching books:", error);
        });
}

// Function to handle book selection
function selectBook(book, searchResults) {
    const inputElement = document.getElementById("borrow_book_name");
    if (!inputElement) {
        console.error("Element with ID 'borrow_book_name' not found.");
        return;
    }
    inputElement.value = book.Title; // Set the selected book title in the input field
    searchResults.style.display = "none"; // Hide the search results container
}
