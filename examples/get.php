<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Chadicus\Marvel\Api;

$publicApiKey = getenv('PUBLIC_KEY');
$privateApiKey = getenv('PRIVATE_KEY');

$client = new Api\Client($privateApiKey, $publicApiKey, new Api\CurlAdapter());

$response = $client->get('characters', 1009351);

$wrapper = $response->getDataWrapper();

$character = $wrapper->getData()->getResults()[0];

echo "{$character->getName()}\n";
echo "{$character->getDescription()}\n";

foreach ($character->getEvents()->getItems() as $event) {
    echo "\t{$event->getName()}\n";
}

