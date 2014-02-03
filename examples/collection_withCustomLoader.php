<?php
class MyComic
{
    private $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Chadicus\Marvel\Api\Client;
use Chadicus\Marvel\Api\CurlAdapter;
use Chadicus\Marvel\Api\Collection;

$publicApiKey = getenv('PUBLIC_KEY');
$privateApiKey = getenv('PRIVATE_KEY');

$client = new Client($privateApiKey, $publicApiKey, new CurlAdapter());

//24 is the id of Bendis.  312 is the id of Deodato
$collection = new Collection(
    $client,
    'comics',
    ['collaborators' => '24,312'],
    function (array $data) {
        return new MyComic($data);
    }
);

echo "{$collection->count()} results found\n";

foreach ($collection as $comic) {
    echo $comic->title . PHP_EOL . $comic->description . PHP_EOL;
}
