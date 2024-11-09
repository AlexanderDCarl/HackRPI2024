$(document).ready(function () {
    function get_search(searchName) {
        $.ajax({
            // I dont care about the api key it was free so imma push it
            url: `https://data.unwrangle.com/api/getter/?platform=amazon_search&search=${searchName}&country_code=us&page=1&api_key=ec69b1867be5be538320790d2d30cb1a91e06e52`,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                console.log('Success:', response);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
        $.ajax({
            url: `https://data.unwrangle.com/api/getter/?platform=walmart_search&search=${searchName}&country_code=us&page=1&api_key=ec69b1867be5be538320790d2d30cb1a91e06e52`,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                console.log('Success:', response);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
        $.ajax({
            url: `https://data.unwrangle.com/api/getter/?platform=costco_search&search=${searchName}&country_code=us&page=1&api_key=ec69b1867be5be538320790d2d30cb1a91e06e52`,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                console.log('Success:', response);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
        $.ajax({
            url: `https://data.unwrangle.com/api/getter/?platform=samsclub_search=${searchName}&country_code=us&page=1&api_key=ec69b1867be5be538320790d2d30cb1a91e06e52`,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                console.log('Success:', response);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    $('.search-button').on('click', () => {
        get_search($('#search-input').val());
    })
});