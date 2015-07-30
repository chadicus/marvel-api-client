#!/usr/bin/env php
<?php
chdir(__DIR__);

$returnStatus = null;
passthru('composer install --dev', $returnStatus);
if ($returnStatus !== 0) {
    exit(1);
}

passthru(
    './vendor/bin/phpcs --standard=' . __DIR__ . '/vendor/chadicus/coding-standard/Chadicus -n src tests *.php',
    $returnStatus
);
if ($returnStatus !== 0) {
    exit(1);
}

passthru('./vendor/bin/phpunit --coverage-html coverage --strict --coverage-clover clover.xml tests', $returnStatus);
if ($returnStatus !== 0) {
    exit(1);
}

$xml = new SimpleXMLElement(file_get_contents('clover.xml'));
foreach ($xml->xpath('//file/metrics') as $metric) {
    if ((int)$metric['elements'] !== (int)$metric['coveredelements']) {
        fputs(STDERR, "Code coverage was NOT 100%\n");
        exit(1);
    }
}

echo "Code coverage was 100%\n";

passthru('./vendor/bin/phploc --quiet --log-xml="phploc.xml" src', $returnStatus);
if ($returnStatus !== 0) {
    exit(1);
}

$xml = new SimpleXMLElement(file_get_contents('phploc.xml'));
$averageClassLength = (int)$xml->xpath('//llocByNoc')[0];
if ($averageClassLength > 200) {
    fputs(STDERR, "Average number of lines per class exceeds 200\n");
    exit(1);
}

echo "Average lines per class is {$averageClassLength}\n";

$namespaceCount = (int)$xml->xpath('//namespaces')[0];
$classCount = (int)$xml->xpath('//classes')[0];
$methodCount = (int)$xml->xpath('//methods')[0];

$averageMethodCount = round($methodCount / $classCount);
if ($averageMethodCount > 10) {
    fputs(STDERR, "Average number of methods per class exceeds 10\n");
    exit(1);
}

echo "Average methods per class is {$averageMethodCount}\n";

$averageClassCount = round($classCount / $namespaceCount);
if ($averageClassCount > 15) {
    fputs(STDERR, "Average number of classes per namespace exceeds 15\n");
    exit(1);
}

echo "Average classes per namespace is {$averageClassCount}\n";

unlink('phploc.xml');
