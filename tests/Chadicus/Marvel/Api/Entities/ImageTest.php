<?php
namespace Chadicus\Marvel\Api\Entities;

/**
 * Unit tests for the Image class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Image
 */
final class ImageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify basic behavior of getPath.
     *
     * @test
     * @covers ::__construct
     * @covers ::getPath
     *
     * @return void
     */
    public function getPath()
    {
        $this->assertSame('a path', (new Image('a path', 'not under test'))->getPath());
    }

    /**
     * Verify basic behavior of getExtension.
     *
     * @test
     * @covers ::__construct
     * @covers ::getExtension
     *
     * @return void
     */
    public function getExtension()
    {
        $this->assertSame('an extension', (new Image('not under test', 'an extension'))->getExtension());
    }

    /**
     * Verify invalid constructor parameters cause exceptions.
     *
     * @param mixed $path      The full URL (including scheme, domain, and path).
     * @param mixed $extension The text identifier for the URL.
     *
     * @test
     * @covers ::__construct
     * @dataProvider constructorBadData
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function constructWithInvalidParameters($path, $extension)
    {
        new Image($path, $extension);
    }

    /**
     * Data provider for constructWithInvalidParameters.
     *
     * @return array
     */
    public function constructorBadData()
    {
        return [
            'path is empty' => ['', 'an extension'],
            'path is whitespace' => ["\t \n", 'an extension'],
            'path is not a string' => [true, 'an extension'],
            'extension is empty' => ['a path', ''],
            'extension is whitespace' => ['a path', "\n \t"],
            'extension is not a string' => ['a path', false],
        ];
    }
}
