<?php

require_once __DIR__ . '/vendor/autoload.php';

use Chadicus\Marvel\Api\Client;
use Chadicus\Marvel\Api\Entities\ImageVariant;
use DominionEnterprises\Util\Arrays;

$nameStartsWith = Arrays::get($_POST, 'nameStartsWith');
?>

<html>
    <head>
        <style>
            label { display: inline-block; width: 140px; text-align: right; }
            img { max-width:800px; }
        </style>
    </head>
    <body>
        <form method="POST" action="/">
            <fieldset>
                <legend>Search Characters</legend>
                <br />
                <label for="nameStartsWith">Name</label>
                <input type="text" name="nameStartsWith" size="32" value="<?=$nameStartsWith?>" />
                <br />
                <input type="Submit" value="Search" />
            </fieldset>
        </form>
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <?php
                $client = new Client(getenv('PRIVATE_KEY'), getenv('PUBLIC_KEY'));
                $dataWrapper = $client->search('characters', ['nameStartsWith' => $nameStartsWith]);
                $count = 0;
            ?>
            <h3><?=$dataWrapper->getData()->getTotal()?> characters found</h3>
            <table border="1">
                <tbody>
                    <tr>
                    <?php foreach ($dataWrapper->getData()->getResults() as $character): ?>
                        <?php if ($count++ % 5 === 0): ?>
                            </tr><tr>
                        <?php endif; ?>
                        <td>
                            <p><?=$character->getName()?></p>
                            <a href="<?=$character->getUrls()[0]->getUrl()?>">
                                <img src="<?=$character->getThumbnail()->getUrl(ImageVariant::PORTRAIT_XLARGE())?>" />
                            </a>
                        </td>
                    <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
            <center><?=$dataWrapper->getAttributionHTML()?></center>
        <?php endif; ?>
    </body>
</html>
