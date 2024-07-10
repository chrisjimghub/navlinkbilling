if (typeof showSpinner != 'function') {
    function showSpinner() {
        $('#spinner').removeClass('d-none'); // Remove 'd-none' to show spinner
    }
}

if (typeof hideSpinner != 'function') {
    function hideSpinner() {
        $('#spinner').addClass('d-none'); // Add 'd-none' to hide spinner
    }
}