$(document).ready(function () {
    function search(){

        const urlParams = new URLSearchParams(window.location.search).get('q');
        console.log(urlParams);

        if (urlParams)
            get_search(urlParams);

    }

    search();

    function get_search(searchName) {
        $.ajax({
            url: '../resources/php/api.php',
            type: 'GET',
            dataType: 'json',
            data: { action: searchName  },
            success: function(response) {
                if (response.error) {
                    console.error('Error:', response.error);
                    $('#results').html(`<p>Error: ${response.error}</p>`);
                } else {
                    console.log(response.amazon);
                    console.log(response.walmart);
                    console.log(response.target);
                    console.log(response.costco);

                    renderResults(response.amazon, 'amazon');
                    renderResults(response.walmart, 'walmart');
                    renderResults(response.target, 'target');
                    renderResults(response.costco, 'costco');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                $('#results').html('<p>An error occurred while fetching data.</p>');
            }
        });
    }

    function renderResults(data) {
        let platformHTML = ``;
        if (data && data.results && data.results.length > 0) {
            platformHTML += '<ul>';
            data.results.forEach(item => {
                platformHTML += `
                    <li>
                        <strong>${item.product_name}</strong> - ${item.price || 'Price not available'}
                    </li>
                `;
            });
            platformHTML += '</ul>';
        } else {
            platformHTML += '<p>No results found.</p>';
        }

        $('#results').append(platformHTML);
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
});
