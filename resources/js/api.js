$(document).ready(function () {

    let urlParams = new URLSearchParams(window.location.search).get('q');
    let pageNumber = 1;
    let api_data = [];

    if (urlParams)
        get_search(urlParams, pageNumber, api_data);

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

    function get_search(searchName, pageNumber, api_data) {
        $.ajax({
            url: '../resources/php/api.php',
            type: 'GET',
            dataType: 'json',
            data: { action: searchName, pageNumber: pageNumber  },
            success: function(response) {
                $('#results').empty();
                check_grey();

                createArray(response, api_data);
                console.log(api_data);
                renderResults(api_data);
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                $('#results').html('<p>An error occurred while fetching data.</p>');
            }
        });
    }
    function createArray(data, api_data) {
        for (const platform in data) {
            if (data.hasOwnProperty(platform)) {
                const platformData = data[platform];
                platformData.forEach(item => {
                    api_data.push([item.price, item.rating, item.url, item.thumbnail || item.image_url, item.name, platform]);
                });
            }
        }
    }

    function sortResults(api_data) {
        const sortOption = document.getElementById('sortDropdown').value;

        if (sortOption === 'priceLowToHigh') {
            return api_data.sort((a, b) => a[0] - b[0]);
        } else if (sortOption === 'priceHighToLow') {
            return api_data.sort((a, b) => b[0] - a[0]);
        } else if (sortOption === 'ratingLowToHigh') {
            return api_data.sort((a, b) => a[1] - b[1]);
        } else if (sortOption === 'ratingHighToLow') {
            return api_data.sort((a, b) => b[1] - a[1]);
        }

        return api_data;
    }

    document.getElementById('sortDropdown').addEventListener('change', function() {
        let sortedData = sortResults(api_data);
        $('#results').empty()
        renderResults(sortedData);
    });

    function renderResults(api_data) {
        let platformHTML = '';
        api_data.forEach(item => {
                platformHTML += `
                <div class="product-item" onclick="window.open('${item[2]}')">
                    <img src="${item[3]}" alt="thumbnail">
                    <div class="product-details">
                        <strong>${item[4]}</strong>`;
                if(item[1] !== null){
                    platformHTML += `<p>${item[0]} $ - ${item[1]} stars</p></div></div>`;
                } else {
                    platformHTML += `<p>${item[0]} $</p></div></div>`;
                }
        });
        $('#results').append(platformHTML);
    }

    $("#nextPage").on("click", function() {
        pageNumber++;
        if (urlParams)
            get_search(urlParams, pageNumber, api_data);

    })
    $("#prevPage").on("click", function() {
        if(pageNumber === 1)
            return;

        pageNumber--;
        if (urlParams)
            get_search(urlParams, pageNumber, api_data);

    })
});
