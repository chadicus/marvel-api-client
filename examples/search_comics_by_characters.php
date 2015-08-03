<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Chadicus\Marvel\Api;

$publicApiKey = getenv('PUBLIC_KEY');
$privateApiKey = getenv('PRIVATE_KEY');

$client = new Api\Client($privateApiKey, $publicApiKey, new Api\CurlAdapter());

//1009165 is the character id for the Avangers
$response = $client->search('comics', ['characters' => 1009165]);

echo "Response Headers\n";
foreach ($response->getHeaders() as $key => $value) {
    echo "{$key}: {$value}\n";
}

$wrapper = $response->getDataWrapper();

echo "{$wrapper->getAttributionText()}\n";

echo "{$wrapper->getData()->getTotal()}  total results found\n";
echo "{$wrapper->getData()->getCount()}  comics in this response\n";

foreach ($wrapper->getData()->getResults() as $comic) {
    echo "{$comic->getTitle()}\n";
}
