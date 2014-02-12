<?php
namespace Chadicus\Marvel\Api\Entities;

/**
 * Unit tests for the ImageVariant class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\ImageVariant
 * @covers ::<private>
 */
final class ImageVariantTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify calling __callStatic with invalid constant name throws exception.
     *
     * @test
     * @covers ::__callStatic
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid value 'invalid' given
     *
     * @return void
     */
    public function callStaticWithInvalidName()
    {
        ImageVariant::invalid();
    }

    /**
     * Verify image variant is casted as string correctly.
     *
     * @test
     * @covers ::__callStatic
     * @covers ::__toString
     *
     * @return void
     */
    public function toString()
    {
        $this->assertSame('standard_fantastic', (string)ImageVariant::STANDARD_FANTASTIC());
    }
}
