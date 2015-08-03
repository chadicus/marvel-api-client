<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Chadicus\Marvel\Api;

$publicApiKey = getenv('PUBLIC_KEY');
$privateApiKey = getenv('PRIVATE_KEY');

$client = new Api\Client($privateApiKey, $publicApiKey, new Api\CurlAdapter());

//24 is the id of Bendis.  312 is the id of Deodato
$collection = new Api\Collection($client, 'comics', ['collaborators' => '24,312']);

echo "{$collection->count()} results found\n";

foreach ($collection as $comic) {
    echo "{$comic->id}: {$comic->title}\n";
}
