# Marvel API Client
[![Build Status](https://travis-ci.org/chadicus/marvel-api-client.svg?branch=master)](https://travis-ci.org/chadicus/marvel-api-client)
[![Code Quality](https://scrutinizer-ci.com/g/chadicus/marvel-api-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chadicus/marvel-api-client/?branch=master)
[![Code Coverage](https://coveralls.io/repos/github/chadicus/marvel-api-client/badge.svg?branch=master)](https://coveralls.io/github/chadicus/marvel-api-client?branch=master)

[![Latest Stable Version](https://poser.pugx.org/chadicus/marvel-api-client/v/stable)](https://packagist.org/packages/chadicus/marvel-api-client)
[![Latest Unstable Version](https://poser.pugx.org/chadicus/marvel-api-client/v/unstable)](https://packagist.org/packages/chadicus/marvel-api-client)
[![License](https://poser.pugx.org/chadicus/marvel-api-client/license)](https://packagist.org/packages/chadicus/marvel-api-client)

[![Total Downloads](https://poser.pugx.org/chadicus/marvel-api-client/downloads)](https://packagist.org/packages/chadicus/marvel-api-client)
[![Daily Downloads](https://poser.pugx.org/chadicus/marvel-api-client/d/daily)](https://packagist.org/packages/chadicus/marvel-api-client)
[![Monthly Downloads](https://poser.pugx.org/chadicus/marvel-api-client/d/monthly)](https://packagist.org/packages/chadicus/marvel-api-client)

A PHP client for use with the [Marvel API](http://developer.marvel.com/docs).

## Requirements

The Marvel API Client requires PHP 5.6 (or later).

##Composer
To add the library as a local, per-project dependency use [Composer](http://getcomposer.org)! Simply add a dependency on `chadicus/marvel-api-client` to your project's `composer.json` file such as:

```json
{
    "require": {
        "chadicus/marvel-api-client": "^2.0"
    }
}
```
##Examples
Examples of use can be found [here](https://github.com/chadicus/marvel-api-client/tree/master/examples).

###Basic Usage

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Chadicus\Marvel\Api\Client;

$publicApiKey = getenv('PUBLIC_KEY');
$privateApiKey = getenv('PRIVATE_KEY');

$client = new Client($privateApiKey, $publicApiKey);

$response = $client->get('characters', 1009351);

//Text to display for attribution requirements
$attributionText = $response->getDataWrapper()->getAttributionText();

$character = $response->getDataWrapper()->getData()->getResults()[0];

echo "{$character->getName()}\n";
echo "{$character->getDescription()}\n";

foreach ($character->getEvents()->getItems() as $event) {
    echo "\t{$event->getName()}\n";
}

```

##Contact
Developers may be contacted at:

 * [Pull Requests](https://github.com/chadicus/marvel-api-client/pulls)
 * [Issues](https://github.com/chadicus/marvel-api-client/issues)

##Project Build
With a checkout of the code get [Composer](http://getcomposer.org) in your PATH and run:

```sh
composer install
./vendor/bin/phpunit
```

## With Great Power Comes Great Responsibility.
When using the `marvel-api-client` you must follow [Marvel's Rules of Attribution](http://developer.marvel.com/documentation/attribution)

