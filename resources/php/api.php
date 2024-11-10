<?php

function get_search($search, $platform, $pageNumber)
{
    // these are useless api keys so idk take em if u want
    $url = "https://data.unwrangle.com/api/getter/?platform=$platform&search=$search&country_code=us&page=$pageNumber&api_key=b04ebc4496cb02fe0d586570b793ad9dae75a9b4";
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPGET, true);

    return $curl;
}

function fetch_multiple_searches($search, $pageNumber)
{
    $mh = curl_multi_init();

    $amazonCurl = get_search($search, "amazon_search", $pageNumber);
    $walmartCurl = get_search($search, "walmart_search", $pageNumber);
    $targetCurl = get_search($search, "target_search", $pageNumber);
    $costcoCurl = get_search($search, "costco_search", $pageNumber);

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

    $amazonData = json_decode($amazonData, true);
    $walmartData = json_decode($walmartData, true);
    $targetData = json_decode($targetData, true);
    $costcoData = json_decode($costcoData, true);

    $combinedResults = [
        'Amazon' => isset($amazonData['results']) ? $amazonData['results'] : [],
        'Walmart' => isset($walmartData['results']) ? $walmartData['results'] : [],
        'Target' => isset($targetData['results']) ? $targetData['results'] : [],
        'Costco' => isset($costcoData['results']) ? $costcoData['results'] : []
    ];

    return $combinedResults;
}

if (isset($_GET['action']) && isset($_GET['pageNumber'])) {
    $search = $_GET['action'];
    $pageNumber = $_GET['pageNumber'];

    $search = htmlspecialchars($search);
    $pageNumber = htmlspecialchars($pageNumber);

    $combinedResults = fetch_multiple_searches($search, $pageNumber);

    echo json_encode($combinedResults);
} else {
    echo json_encode(["error" => "No action specified"]);
}
?>
