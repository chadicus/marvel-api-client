# Marvel API Client
[![Build Status](https://travis-ci.org/chadicus/marvel-api-client.png)](https://travis-ci.org/chadicus/marvel-api-client)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/chadicus/marvel-api-client.svg?style=flat)](https://scrutinizer-ci.com/g/chadicus/marvel-api-client/)
[![Code Coverage](http://img.shields.io/coveralls/chadicus/marvel-api-client.svg?style=flat)](https://coveralls.io/r/chadicus/marvel-api-client)
[![Latest Stable Version](http://img.shields.io/packagist/v/chadicus/marvel-api-client.svg?style=flat)](https://packagist.org/packages/chadicus/marvel-api-client)
[![Total Downloads](http://img.shields.io/packagist/dt/chadicus/marvel-api-client.svg?style=flat)](https://packagist.org/packages/chadicus/marvel-api-client)
[![License](http://img.shields.io/packagist/l/chadicus/marvel-api-client.svg?style=flat)](https://packagist.org/packages/chadicus/marvel-api-client)

A PHP client for use with the Marvel API.  This project is still under heavy development.

## Requirements

The Marvel API Client requires PHP 5.4 (or later).

##Composer
To add the library as a local, per-project dependency use [Composer](http://getcomposer.org)! Simply add a dependency on
`chadicus/marvel-api-client` to your project's `composer.json` file such as:

```json
{
    "require": {
        "chadicus/marvel-api-client": "0.1.1"
    }
}
```
##Documentation
PHP docs for the project can be found [here](http://chadicus.github.io/marvel-api-client).

##Examples
Examples of use can be found [here](https://github.com/chadicus/marvel-api-client/tree/master/examples).

###Basic Usage

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Chadicus\Marvel\Api;

$publicApiKey = getenv('PUBLIC_KEY');
$privateApiKey = getenv('PRIVATE_KEY');

$client = new Api\Client($privateApiKey, $publicApiKey, new Api\CurlAdapter());

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

