$(document).ready(function () {

    // Function to send the search request to the PHP backend
    function get_search(searchName) {
        $.ajax({
            url: './resources/php/api.php',
            type: 'GET',
            dataType: 'json',
            data: { action: searchName  },
            success: function(response) {
                if (response.error) {
                    console.error('Error:', response.error);
                    $('#results').html(`<p>Error: ${response.error}</p>`);
                } else {
                    console.log('Amazon Results:', response.amazon);
                    console.log('Walmart Results:', response.walmart);
                    console.log('Target Results:', response.target);
                    console.log('Costco Results:', response.costco);

                    // Call function to render the results for each platform
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

    // Function to render the search results for each platform
    function renderResults(data, platform) {
        let platformHTML = `<h3>${capitalizeFirstLetter(platform)} Results:</h3>`;
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

        // Append the platform results to the page
        $('#results').append(platformHTML);
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    $('.search-button').on('click', function() {
        const searchTerm = $('#search-input').val();
        if (searchTerm.trim() !== "") {
            $('#results').html('');
            get_search(searchTerm);
        } else {
            $('#results').html('<p>Please enter a search term.</p>');
        }
    });

});
