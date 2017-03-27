<?php
namespace Chadicus\Marvel\Api\Entities;

/**
 * Unit tests for the Image class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Image
 * @covers ::<protected>
 */
final class ImageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Verify basic behavior of getPath.
     *
     * @test
     *
     * @return void
     */
    public function getPath()
    {
        $this->assertSame('a path', (new Image(['path' => 'a path']))->getPath());
    }

    /**
     * Verify basic behavior of getExtension.
     *
     * @test
     *
     * @return void
     */
    public function getExtension()
    {
        $this->assertSame('an extension', (new Image(['extension' => 'an extension']))->getExtension());
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
        $this->assertSame(
            'a url/portrait_small.an extension',
            (new Image(['path' => 'a url', 'extension' => 'an extension']))->getUrl(ImageVariant::PORTRAIT_SMALL())
        );
    }

    /**
     * Verify invalid constructor parameters cause exceptions.
     *
     * @param mixed $path      The full URL (including scheme, domain, and path).
     * @param mixed $extension The text identifier for the URL.
     *
     * @test
     * @dataProvider constructorBadData
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function constructWithInvalidParameters($path, $extension)
    {
        new Image(['path' => $path, 'extension' => $extension]);
    }

    /**
     * Data provider for constructWithInvalidParameters.
     *
     * @return array
     */
    public function constructorBadData()
    {
        return [
            'path is not a string' => [true, 'an extension'],
            'extension is not a string' => ['a path', false],
        ];
    }

    /**
     * Verify basic functionality of fromArray().
     *
     * @test
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

    /**
     * Verify behaviour with null values.
     *
     * @test
     *
     * @return void
     */
    public function constructWithNulls()
    {
        $image = new Image(['path' => null, 'extension' => null]);
        $this->assertNull($image->path);
        $this->assertNull($image->extension);
    }
}
