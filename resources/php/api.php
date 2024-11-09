<?php

function get_search($search, $platform)
{
    $amazon_url = "https://data.unwrangle.com/api/getter/?platform=$platform&search=$search&country_code=us&page=1&api_key=ec69b1867be5be538320790d2d30cb1a91e06e52";

    $curl = curl_init($amazon_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPGET, true);

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

if (isset($_GET['action'])) {
    $search = $_GET['action'];

    $amazonData = get_search($search, "amazon_search");
    $walmartData = get_search($search, "walmart_search");
    $targetData = get_search($search, "target_search");
    $costcoData = get_search($search, "costco_search");

    $combinedResults = [
        'amazon' => json_decode($amazonData, true),
        'walmart' => json_decode($walmartData, true),
        'target' => json_decode($targetData, true),
        'costco' => json_decode($costcoData, true)
    ];

    echo json_encode($combinedResults);

} else {
    echo json_encode(["error" => "No action specified"]);
}
?>