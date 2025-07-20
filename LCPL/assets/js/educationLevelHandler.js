// educationLevelHandler.js

document.querySelectorAll('.eduLevel').forEach(function (radio) {
    radio.addEventListener('change', function () {
        if (this.checked && this.value === 'yes') {
            // Deselect "Yes" and "No" for all other education levels
            document.querySelectorAll('.eduLevel').forEach(function (otherRadio) {
                if (otherRadio.dataset.level !== radio.dataset.level) {
                    otherRadio.checked = false;
                }
            });
        }
    });
});
