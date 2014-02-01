<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Chadicus\Marvel\Api\Client;
use Chadicus\Marvel\Api\CurlAdapter;

$publicApiKey = getenv('PUBLIC_KEY');
$privateApiKey = getenv('PRIVATE_KEY');

$client = new Client($privateApiKey, $publicApiKey, new CurlAdapter());

//1009165 is the character id for the Avangers
$response = $client->get('characters', 1009351);

echo "Response Headers\n";
foreach ($response->getHeaders() as $key => $value) {
    echo "{$key}: {$value}\n";
}

$character = $response->getBody()['data']['results'][0];
echo "{$character['name']}\n";
echo "{$character['description']}\n";
foreach ($character['events']['items'] as $event) {
    echo "\t{$event['name']}\n";
}

