<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Chadicus\Marvel\Api;
use Chadicus\Marvel\Api\Entities\Comic;

$publicApiKey = getenv('PUBLIC_KEY');
$privateApiKey = getenv('PRIVATE_KEY');

$client = new Api\Client($privateApiKey, $publicApiKey, new Api\CurlAdapter());

//24 is the id of Bendis.  312 is the id of Deodato
$comics = Comic::findAll($client, ['dateDescriptor' => 'thisWeek']);

foreach ($comics as $comic) {
    echo str_repeat('=', 80) . PHP_EOL;
    echo "ID: {$comic->getId()}\n";
    echo "DIGITAL ID: {$comic->getDigitalId()}\n";
    echo "TITLE: {$comic->getTitle()}\n";
    echo "ISSUE NUMBER: {$comic->getIssueNumber()}\n";
    echo "VARIANT DESCRIPTION: {$comic->getVariantDescription()}\n";
    echo "DESCRIPTION: {$comic->getDescription()}\n";
    echo "MODIFIED: {$comic->getModified()->format('Y-m-d')}\n";
    echo "ISBN: {$comic->getIsbn()}\n";
    echo "UPC: {$comic->getUpc()}\n";
    echo "DIAMOND CODE: {$comic->getDiamondCode()}\n";
    echo "EAN: {$comic->getEan()}\n";
    echo "ISSN: {$comic->getIssn()}\n";
    echo "FORMAT: {$comic->getFormat()}\n";
    echo "PAGE COUNT: {$comic->getPageCount()}\n";
    echo "RESOURCE URI: {$comic->getResourceURI()}\n";
    echo 'TEXT OBJECTS (' . count($comic->getTextObjects()) . "):\n";
    foreach ($comic->getTextObjects() as $textObject) {
        echo "\tTYPE: {$textObject->getType()}\n";
        echo "\tLANGUAGE: {$textObject->getLanguage()}\n";
        echo "\tTEXT: {$textObject->getText()}\n";
    }

    echo 'URLS (' . count($comic->getUrls()) . "):\n";
    foreach ($comic->getUrls() as $url) {
        echo "\tTYPE: {$url->getType()}\n";
        echo "\tURL: {$url->getUrl()}\n";
    }

    echo "SERIES:\n";
    echo "\tRESOURCE URI: {$comic->getSeries()->getResourceURI()}\n";
    echo "\tNAME: {$comic->getSeries()->getName()}\n";

    echo 'VARIANTS (' . count($comic->getVariants()) . "):\n";
    foreach ($comic->getVariants() as $variant) {
        echo "\tRESOURCE URI: {$variant->getResourceURI()}\n";
        echo "\tNAME: {$variant->getName()}\n";
    }

    echo 'COLLECTIONS (' . count($comic->getCollections()) . "):\n";
    foreach ($comic->getCollections() as $collection) {
        echo "\tRESOURCE URI: {$collection->getResourceURI()}\n";
        echo "\tNAME: {$collection->getName()}\n";
    }

    echo 'COLLECTED ISSUES (' . count($comic->getCollectedIssues()) . "):\n";
    foreach ($comic->getCollectedIssues() as $collectedIssue) {
        echo "\tRESOURCE URI: {$collectedIssue->getResourceURI()}\n";
        echo "\tNAME: {$collectedIssue->getName()}\n";
    }

    echo 'DATES (' . count($comic->getDates()) . "):\n";
    foreach ($comic->getDates() as $date) {
        echo "\tTYPE: {$date->getType()}\n";
        echo "\tDATE: {$date->getDate()->format('r')}\n";
    }

    echo 'PRICES (' . count($comic->getPrices()) . "):\n";
    foreach ($comic->getPrices() as $price) {
        echo "\tTYPE: {$price->getType()}\n";
        echo "\tPRICE: {$price->getPrice()}\n";
    }

    echo "THUMBNAIL:\n";
    echo "\tPATH: {$comic->getThumbnail()->getPath()}\n";
    echo "\tEXTENSION: {$comic->getThumbnail()->getExtension()}\n";

    echo 'IMAGES (' . count($comic->getImages()) . "):\n";
    foreach ($comic->getImages() as $image) {
        echo "\tPATH: {$image->getPath()}\n";
        echo "\tEXTENSION: {$image->getExtension()}\n";
    }

    echo "CREATORS:\n";
    echo "\tAVAILABLE: {$comic->getCreators()->getAvailable()}\n";
    echo "\tRETURNED: {$comic->getCreators()->getReturned()}\n";
    echo "\tCOLLECTION URI: {$comic->getCreators()->getCollectionURI()}\n";
    echo "\tITEMS (" . count($comic->getCreators()->getItems()) . ")\n";
    foreach ($comic->getCreators()->getItems() as $item) {
        echo "\t\tRESOURCE URI: {$item->getResourceURI()}\n";
        echo "\t\tNAME: {$item->getName()}\n";
        echo "\t\tROLE: {$item->getRole()}\n";
        echo "\t\tTYPE: {$item->getType()}\n";
    }

    echo "STORIES:\n";
    echo "\tAVAILABLE: {$comic->getStories()->getAvailable()}\n";
    echo "\tRETURNED: {$comic->getStories()->getReturned()}\n";
    echo "\tCOLLECTION URI: {$comic->getStories()->getCollectionURI()}\n";
    echo "\tITEMS (" . count($comic->getStories()->getItems()) . ")\n";
    foreach ($comic->getStories()->getItems() as $item) {
        echo "\t\tRESOURCE URI: {$item->getResourceURI()}\n";
        echo "\t\tNAME: {$item->getName()}\n";
        echo "\t\tROLE: {$item->getRole()}\n";
        echo "\t\tTYPE: {$item->getType()}\n";
    }

    echo "EVENTS:\n";
    echo "\tAVAILABLE: {$comic->getEvents()->getAvailable()}\n";
    echo "\tRETURNED: {$comic->getEvents()->getReturned()}\n";
    echo "\tCOLLECTION URI: {$comic->getEvents()->getCollectionURI()}\n";
    echo "\tITEMS (" . count($comic->getEvents()->getItems()) . ")\n";
    foreach ($comic->getEvents()->getItems() as $item) {
        echo "\t\tRESOURCE URI: {$item->getResourceURI()}\n";
        echo "\t\tNAME: {$item->getName()}\n";
        echo "\t\tROLE: {$item->getRole()}\n";
        echo "\t\tTYPE: {$item->getType()}\n";
    }

    echo "CHARACTERS:\n";
    echo "\tAVAILABLE: {$comic->getCharacters()->getAvailable()}\n";
    echo "\tRETURNED: {$comic->getCharacters()->getReturned()}\n";
    echo "\tCOLLECTION URI: {$comic->getCharacters()->getCollectionURI()}\n";
    echo "\tITEMS (" . count($comic->getCharacters()->getItems()) . ")\n";
    foreach ($comic->getCharacters()->getItems() as $item) {
        echo "\t\tRESOURCE URI: {$item->getResourceURI()}\n";
        echo "\t\tNAME: {$item->getName()}\n";
        echo "\t\tROLE: {$item->getRole()}\n";
        echo "\t\tTYPE: {$item->getType()}\n";
    }

}
