<?php

function get_search($search, $platform, $pageNumber)
{
    $url = "https://data.unwrangle.com/api/getter/?platform=$platform&search=$search&country_code=us&page=$pageNumber&api_key=31a597b52a51b1c654403a9ca5a7d6ce71e996df";
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

    $combinedResults = [];

    if (isset($amazonData['results'])) {
        foreach ($amazonData['results'] as $item) {
            $combinedResults[] = array_merge($item, ['platform' => 'amazon']);
        }
    }

    if (isset($walmartData['results'])) {
        foreach ($walmartData['results'] as $item) {
            $combinedResults[] = array_merge($item, ['platform' => 'walmart']);
        }
    }

    if (isset($targetData['results'])) {
        foreach ($targetData['results'] as $item) {
            $combinedResults[] = array_merge($item, ['platform' => 'target']);
        }
    }

    if (isset($costcoData['results'])) {
        foreach ($costcoData['results'] as $item) {
            $combinedResults[] = array_merge($item, ['platform' => 'costco']);
        }
    }

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
