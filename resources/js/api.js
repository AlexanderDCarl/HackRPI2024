$(document).ready(function () {

    let urlParams = new URLSearchParams(window.location.search).get('q');
    let pageNumber = 1;

    if (urlParams)
        get_search(urlParams, pageNumber);

    function check_grey() {
        if (pageNumber === 1) {
            $('#prevPage').css({
                'background-color': '#9e9e9e',
                'cursor': 'not-allowed'
            }).off('mouseenter mouseleave');

        } else {
            $('#prevPage').css({
                'background-color': '#efefef',
                'cursor': 'pointer'
            }).on('mouseenter mouseleave', function () {
                $(this).css('background-color', '#f0f0f0');
            });
        }
    }
    check_grey();
    function get_search(searchName, pageNumber) {
        $.ajax({
            url: '../resources/php/api.php',
            type: 'GET',
            dataType: 'json',
            data: { action: searchName, pageNumber: pageNumber  },
            success: function(response) {
                if (response.error) {
                    console.error('Error:', response.error);
                    $('#results').html(`<p>Error: ${response.error}</p>`);
                } else {
                    console.log(response);
                    $('#results').empty();
                    check_grey();
                    renderResults(response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                $('#results').html('<p>An error occurred while fetching data.</p>');
            }
        });
    }

    function renderResults(data) {
        let platformHTML = '';
        if (data && data.length > 0) {
            data.forEach(item => {
                platformHTML += `
                <div class="product-item" onclick="window.open('${item.url}')">
                    <img src="${item.thumbnail || item.image_url}" alt="thumbnail">
                    <div class="product-details">
                        <strong>${item.name}</strong>
                        <p>${item.price} $ - ${item.rating} stars</p>
                    </div>
                </div>
            `;
            });
            $('#results').append(platformHTML);
        } else {
            platformHTML += '<p>No results found.</p>';
            $('#results').append(platformHTML);
        }
    }

    $("#nextPage").on("click", function() {
        pageNumber++;
        if (urlParams)
            get_search(urlParams, pageNumber);

    })
    $("#prevPage").on("click", function() {
        if(pageNumber === 1)
            return;

        pageNumber--;
        if (urlParams)
            get_search(urlParams, pageNumber);

    })
});
