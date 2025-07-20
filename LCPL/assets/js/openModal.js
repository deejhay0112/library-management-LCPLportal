function openModal() {
    document.getElementById("borrowModal").style.display = "flex"; // Use flex to center the modal

    // Set the default borrow date to today when the modal opens
    const borrowDateInput = document.getElementById("borrowDate");
    if (!borrowDateInput.value) { // Only set default if the input is empty
        const currentDate = new Date().toISOString().split("T")[0]; // Get today's date in YYYY-MM-DD format
        borrowDateInput.value = currentDate; // Set default value to today's date
    }
}

function closeModal() {
    document.getElementById("borrowModal").style.display = "none";
}

document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById("dateInput");
    const timeInput = document.getElementById("timeInput");
    const currentDate = new Date();
    dateInput.value = currentDate.toISOString().split("T")[0]; // Set the current date
    timeInput.value = currentDate.toLocaleTimeString(); // Set the current time

    // Update the time every second
    function updateTime() {
        const now = new Date();
        timeInput.value = now.toLocaleTimeString(); // Update the current time
    }

    setInterval(updateTime, 1000); // Update time every second
});
