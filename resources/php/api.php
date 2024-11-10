<?php

function get_search($search, $platform)
{
    $url = "https://data.unwrangle.com/api/getter/?platform=$platform&search=$search&country_code=us&page=1&api_key=ec69b1867be5be538320790d2d30cb1a91e06e52";
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPGET, true);

    return $curl;
}

function fetch_multiple_searches($search)
{
    $mh = curl_multi_init();

    $amazonCurl = get_search($search, "amazon_search");
    $walmartCurl = get_search($search, "walmart_search");
    $targetCurl = get_search($search, "target_search");
    $costcoCurl = get_search($search, "costco_search");

    curl_multi_add_handle($mh, $amazonCurl);
    curl_multi_add_handle($mh, $walmartCurl);
    curl_multi_add_handle($mh, $targetCurl);
    curl_multi_add_handle($mh, $costcoCurl);

    $running = null;
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);

    $amazonData = curl_multi_getcontent($amazonCurl);
    $walmartData = curl_multi_getcontent($walmartCurl);
    $targetData = curl_multi_getcontent($targetCurl);
    $costcoData = curl_multi_getcontent($costcoCurl);

    curl_multi_remove_handle($mh, $amazonCurl);
    curl_multi_remove_handle($mh, $walmartCurl);
    curl_multi_remove_handle($mh, $targetCurl);
    curl_multi_remove_handle($mh, $costcoCurl);

    curl_multi_close($mh);

    return [
        'amazon' => json_decode($amazonData, true),
        'walmart' => json_decode($walmartData, true),
        'target' => json_decode($targetData, true),
        'costco' => json_decode($costcoData, true)
    ];
}

if (isset($_GET['action'])) {
    $search = $_GET['action'];

    $combinedResults = fetch_multiple_searches($search);

    echo json_encode($combinedResults);
} else {
    echo json_encode(["error" => "No action specified"]);
}
?>
