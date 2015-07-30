<?php
namespace Chadicus\Marvel\Api\Entities;

/**
 * Unit tests for the Url class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Url
 */
final class UrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify basic behavior of getUrl.
     *
     * @test
     * @covers ::__construct
     * @covers ::getUrl
     *
     * @return void
     */
    public function getUrl()
    {
        $this->assertSame('a url', (new Url('not under test', 'a url'))->getUrl());
    }

    /**
     * Verify basic behavior of getType.
     *
     * @test
     * @covers ::__construct
     * @covers ::getType
     *
     * @return void
     */
    public function getType()
    {
        $this->assertSame('a type', (new Url('a type', 'not under test'))->getType());
    }

    /**
     * Verify invalid constructor parameters cause exceptions.
     *
     * @param mixed $type The text identifier for the URL.
     * @param mixed $url  The full URL (including scheme, domain, and path).
     *
     * @test
     * @covers ::__construct
     * @dataProvider constructorBadData
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function constructWithInvalidParameters($type, $url)
    {
        new Url($type, $url);
    }

    /**
     * Data provider for constructWithInvalidParameters.
     *
     * @return array
     */
    public function constructorBadData()
    {
        return [
            'type is empty' => ['', 'a url'],
            'type is whitespace' => ["\t \n", 'a url'],
            'type is not a string' => [true, 'a url'],
            'url is empty' => ['a type', ''],
            'url is whitespace' => ['a type', "\n \t"],
            'url is not a string' => ['a type', false],
        ];
    }

    /**
     * Verify invalid constructor parameters cause exceptions.
     *
     * @param mixed $type The text identifier for the URL.
     * @param mixed $url  The full URL (including scheme, domain, and path).
     *
     * @test
     * @covers ::__construct
     * @covers ::getUrl
     * @covers ::getType
     * @dataProvider constructorGoodData
     *
     * @return void
     */
    public function constructWithValidParameters($type, $url)
    {
        $obj = new Url($type, $url);
        $this->assertSame($type, $obj->getType());
        $this->assertSame($url, $obj->getUrl());
    }

    /**
     * Data provider for constructWithValidParameters.
     *
     * @return array
     */
    public function constructorGoodData()
    {
        return [
            'type is null, url is null' => [null, null],
            'type is string, url is null' => ['a type', null],
            'type is null, url is string' => [null, 'a url'],
            'type is string, url is string' => ['a type', 'a url'],
        ];
    }

    /**
     * Verify basic behavior of fromArray().
     *
     * @test
     * @covers ::fromArray
     *
     * @return void
     */
    public function fromArray()
    {
        $url = Url::fromArray(['type' => 'a type', 'url' => 'a url']);
        $this->assertSame('a type', $url->getType());
        $this->assertSame('a url', $url->getUrl());
    }

    /**
     * Verify fromArray throws when input is invalid.
     *
     * @test
     * @covers ::fromArray
     * @expectedException \Exception
     *
     * @return void
     */
    public function fromArrayWithInvalidInput()
    {
        Url::fromArray(['type' => 'a type', 'url' => true]);
    }

    /**
     * Verify basic behavior of fromArrays().
     *
     * @test
     * @covers ::fromArrays
     *
     * @return void
     */
    public function fromArrays()
    {
        $urls = Url::fromArrays(
            [
                ['type' => 'a type', 'url' => 'a url'],
                ['type' => 'another type', 'url' => 'another url'],
            ]
        );

        $this->assertSame(2, count($urls));
        $this->assertSame('a type', $urls[0]->getType());
        $this->assertSame('a url', $urls[0]->getUrl());
        $this->assertSame('another type', $urls[1]->getType());
        $this->assertSame('another url', $urls[1]->getUrl());
    }

    /**
     * Verify fromArrays throws when input is invalid.
     *
     * @test
     * @covers ::fromArrays
     * @expectedException \Exception
     *
     * @return void
     */
    public function fromArraysWithInvalidInput()
    {
        Url::fromArrays(
            [
                ['type' => 'a type', 'url' => 'a url'],
                ['type' => '', 'url' => 2],
            ]
        );
    }
}
