$(document).ready(function () {
    $('.search-button').on('click', function() {
        const searchTerm = $('#search-input').val();
        if (searchTerm.trim() !== "") {
            window.location.href=`./html/search?q=${searchTerm}`
        }
    });
});
