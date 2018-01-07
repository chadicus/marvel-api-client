<?php
require_once __DIR__ . '/vendor/autoload.php';

use Chadicus\Marvel\Api\Cache\Serializer;
use Chadicus\Marvel\Api\Client;
use Chadicus\Marvel\Api\Collection;
use Chadicus\Psr\SimpleCache\MongoCache;
use MongoDB\Client as MongoClient;

$publicApiKey = getenv('PUBLIC_KEY');
$privateApiKey = getenv('PRIVATE_KEY');

$collection = (new MongoClient('mongodb://localhost:27017'))->selectDatabase('marvel')->selectCollection('cache');

$cache = new MongoCache($collection, new Serializer());

$client = new Client($privateApiKey, $publicApiKey, null, $cache);

//24 is the id of Bendis.  312 is the id of Deodato
$collection = new Collection($client, 'comics', ['collaborators' => '24,312']);

foreach ($collection as $comic) {
    echo "{$comic->id}: {$comic->title}\n";
}
