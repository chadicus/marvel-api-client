<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Chadicus\Marvel\Api\Client;
use Chadicus\Marvel\Api\Entities\Character;

$publicApiKey = getenv('PUBLIC_KEY');
$privateApiKey = getenv('PRIVATE_KEY');

$client = new Client($privateApiKey, $publicApiKey);

//24 is the id of Bendis.  312 is the id of Deodato
$characters = Character::findAll($client, ['name' => 'spider-man']);

foreach ($characters as $character) {
    echo $character->getName() . PHP_EOL;
    foreach ($character->getUrls() as $url) {
        echo "\t{$url->getType()}\n";
    }

    echo "{$character->getEvents()->getAvailable()} events available\n";
    echo "Here are the first {$character->getEvents()->getReturned()}\n";
    foreach ($character->getEvents()->getItems() as $item) {
        echo "\t{$item['name']}\n";
    }

    echo "{$character->getSeries()->getAvailable()} series available\n";
    echo "Here are the first {$character->getSeries()->getReturned()}\n";
    foreach ($character->getSeries()->getItems() as $item) {
        echo "\t{$item['name']}\n";
    }
}
