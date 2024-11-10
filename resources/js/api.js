$(document).ready(function () {

    let urlParams = new URLSearchParams(window.location.search).get('q');
    let pageNumber = 1;
    let api_data = [];

    function getCookie(name) {
        let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    }

    let selectedStores = getCookie('selectedStores') ? JSON.parse(getCookie('selectedStores')) : {
        Amazon: true,
        Walmart: true,
        Costco: true,
        Target: true
    };

    updateSidebar(selectedStores);

    if (urlParams)
        get_search(urlParams, pageNumber, selectedStores);

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

    function get_search(searchName, pageNumber, selectedStores) {
        $.ajax({
            url: '../resources/php/api.php',
            type: 'GET',
            dataType: 'json',
            data: { action: searchName, pageNumber: pageNumber },
            success: function (response) {
                $('#results').empty();
                check_grey();
                createArray(response);
                console.log(selectedStores);
                let filteredData = filterResults(api_data, selectedStores);
                renderResults(filteredData);
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', error);
                $('#results').html('<p>An error occurred while fetching data.</p>');
            }
        });
    }

    function createArray(data) {
        api_data = [];
        for (const platform in data) {
            if (data.hasOwnProperty(platform)) {
                const platformData = data[platform];
                platformData.forEach(item => {
                    if (item.price !== 0) {
                        api_data.push([item.price, item.rating, item.url, item.thumbnail || item.image_url, item.name, platform]);
                    }
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

    function updateSidebar(selectedStores) {
        $('.store-toggle').each(function () {
            let store = $(this).data('store');
            $(this).toggleClass('active', selectedStores[store]);
        });
    }

    function renderResults(api_data) {
        let platformHTML = '';
        api_data.forEach(item => {
            platformHTML += `
                <div class="product-item">
                    <img src="${item[3]}" alt="thumbnail">
                    <div class="product-details">
                        <strong>${item[4]}</strong>
                        <p>Store: ${item[5]}</p>
                        <button class="addToCart" style="z-index: 3" data-url="${item[2]}" data-image="${item[3]}" data-name="${item[4]}" data-price="${item[0]}" data-store="${item[5]}">
                             <label>Add To Cart</label>
                        </button>`;
            if (item[1] !== null) {
                platformHTML += `<p>${item[0]} $ - ${item[1]} stars</p></div></div>`;
            } else {
                platformHTML += `<p>${item[0]} $</p></div></div>`;
            }
        });
        $('#results').append(platformHTML);
    }

    $('#results').on('click', '.addToCart', function(event) {
        event.stopPropagation();

        let productURL = $(this).data('url');
        let productImage = $(this).data('image');
        let productName = $(this).data('name');
        let productPrice = $(this).data('price');
        let productStore = $(this).data('store');

        $.ajax({
            url: '../resources/php/addToList.php',
            type: 'POST',
            data: {
                url: productURL,
                image: productImage,
                name: productName,
                price: productPrice,
                store: productStore
            },
            success: function(response) {
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                alert('Error adding product to cart.');
            }
        });
    });

    $('#results').on('click', '.product-item', function() {
        let productURL = $(this).find('.addToCart').data('url');
        window.open(productURL, '_blank');
    });

    $('#sortDropdown').on('change', function () {
        let sortedData = sortResults(api_data);
        $('#results').empty();
        renderResults(sortedData);
    });


    $('.store-toggle').on('click', function () {
        let store = $(this).data('store');
        selectedStores[store] = !selectedStores[store];
        setCookie('selectedStores', JSON.stringify(selectedStores), 365);
        updateSidebar(selectedStores);
        let filteredData = filterResults(api_data, selectedStores);
        let sortedData = sortResults(filteredData);
        $('#results').empty();
        renderResults(sortedData);
    });

    function filterResults(api_data, selectedStores) {
        return api_data.filter(item => selectedStores[item[5]]);
    }

    function setCookie(name, value, days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        let expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }

    $("#nextPage").on("click", function () {
        pageNumber++;
        if (urlParams) {
            get_search(urlParams, pageNumber, selectedStores);
        }
    });

    $("#prevPage").on("click", function () {
        if (pageNumber > 1) {
            pageNumber--;
            if (urlParams) {
                get_search(urlParams, pageNumber, selectedStores);
            }
        }
    });
});
