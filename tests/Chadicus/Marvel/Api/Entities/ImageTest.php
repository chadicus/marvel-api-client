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
     * Verify basic behavior of getUrl.
     *
     * @test
     * @covers ::getUrl
     *
     * @return void
     */
    public function getUrl()
    {
        $this->assertSame('a url/portrait_small.an extension', (new Image('a url', 'an extension'))->getUrl(ImageVariant::PORTRAIT_SMALL()));
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

    /**
     * Verify basic functionality of fromArray().
     *
     * @test
     * @covers ::fromArray
     *
     * @return void
     */
    public function fromArray()
    {
        $image = Image::fromArray(['path' => 'a path', 'extension' => 'an extension']);
        $this->assertSame('a path', $image->getPath());
        $this->assertSame('an extension', $image->getExtension());
    }

    /**
     * Verify fromArray() throws filter exception.
     *
     * @test
     * @covers ::fromArray
     * @expectedException \Exception
     *
     * @return void
     */
    public function fromArrayInvalidPath()
    {
        $image = Image::fromArray(['path' => 1, 'extension' => 'an extension']);
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
        $images = Image::fromArrays(
            [
                ['path' => 'a path', 'extension' => 'an extension'],
                ['path' => 'another path', 'extension' => 'another extension'],
            ]
        );

        $this->assertSame(2, count($images));
        $this->assertSame('an extension', $images[0]->getExtension());
        $this->assertSame('a path', $images[0]->getPath());
        $this->assertSame('another extension', $images[1]->getExtension());
        $this->assertSame('another path', $images[1]->getPath());
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
        Image::fromArrays(
            [
                ['path' => 'a path', 'extension' => 'an extension'],
                ['path' => 'another path', 'extension' => true],
            ]
        );
    }
}
