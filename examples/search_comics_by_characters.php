<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Chadicus\Marvel\Api\Client;

$publicApiKey = getenv('PUBLIC_KEY');
$privateApiKey = getenv('PRIVATE_KEY');

$client = new Client($privateApiKey, $publicApiKey);

//1009165 is the character id for the Avangers
$wrapper = $client->search('comics', ['characters' => 1009165]);

echo "{$wrapper->getAttributionText()}\n";

echo "{$wrapper->getData()->getTotal()}  total results found\n";
echo "{$wrapper->getData()->getCount()}  comics in this response\n";

foreach ($wrapper->getData()->getResults() as $comic) {
    echo "{$comic->getTitle()}\n";
}
