<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Chadicus\Marvel\Api\Client;
use Chadicus\Marvel\Api\CurlAdapter;

$publicApiKey = getenv('PUBLIC_KEY');
$privateApiKey = getenv('PRIVATE_KEY');

$client = new Client($privateApiKey, $publicApiKey, new CurlAdapter());

//1009165 is the character id for the Avangers
$response = $client->search('comics', ['characters' => 1009165]);

echo "Response Headers\n";
foreach ($response->getHeaders() as $key => $value) {
    echo "{$key}: {$value}\n";
}

echo $response->getBody()['data']['total'] . " total results found\n";
echo $response->getBody()['data']['count'] . " comics in this response\n";

foreach ($response->getBody()['data']['results'] as $comic) {
    echo $comic['title'] . PHP_EOL;
}
