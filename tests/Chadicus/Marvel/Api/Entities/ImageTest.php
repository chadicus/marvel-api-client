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
     * @expectedException \Chadicus\Filter\Exception
     *
     * @return void
     */
    public function fromArrayInvalidPath()
    {
        $image = Image::fromArray(['path' => 1, 'extension' => 'an extension']);
    }
}
