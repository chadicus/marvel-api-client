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
}
