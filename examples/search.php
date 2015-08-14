<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Chadicus\Marvel\Api\Client;

$publicApiKey = getenv('PUBLIC_KEY');
$privateApiKey = getenv('PRIVATE_KEY');

$client = new Client($privateApiKey, $publicApiKey);

//1009165 is the character id for the Avangers
$comics = $client->comics(['characters' => 1009165]);

foreach ($comics as $comic) {
    echo "{$comic->getTitle()}\n";
}
