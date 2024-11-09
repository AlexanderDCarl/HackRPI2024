$(document).ready(function () {
    $.ajax({
        ec69b1867be5be538320790d2d30cb1a91e06e52
        url: 'https://api.example.com/data',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Success:', response);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});