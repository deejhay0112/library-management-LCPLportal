// searchBooks.js

function searchBooks() {
    const input = document.getElementById('borrowedBook');
    const query = input.value;

    // Clear previous results
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = '';

    if (query.length < 2) {
        resultsContainer.style.display = 'none'; // Hide results if query is too short
        return;
    }

    // Fetch books from the server
    fetch('fetch_borrowbooks.php?search=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                resultsContainer.style.display = 'block'; // Show results
                data.forEach(book => {
                    const resultDiv = document.createElement('div');
                    resultDiv.textContent = book.Title + ' by ' + book.Author_Title + ' by ' + book.accession_no;
                    resultDiv.onclick = () => selectBook(book.Title);
                    resultsContainer.appendChild(resultDiv);
                });

            } else {
                resultsContainer.style.display = 'none'; // Hide if no results
            }
        })
        .catch(error => console.error('Error fetching books:', error));
}

function selectBook(title) {
    const input = document.getElementById('borrowedBook');
    input.value = title; // Set the input value to the selected book title
    document.getElementById('searchResults').style.display = 'none'; // Hide results
}
