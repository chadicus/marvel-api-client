<?php
namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Util;

/**
 * Unit tests for the AbstractEntity class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\AbstractEntity
 */
final class AbstractEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify basic functionality of fromArrays()
     *
     * @test
     * @covers ::fromArrays
     *
     * @return void
     */
    public function fromArrays()
    {
        $actual = SimpleEntity::fromArrays([['foo'], ['bar']]);
        $this->assertSame(2, count($actual));

        $this->assertSame(['foo'], $actual[0]->input);
        $this->assertSame(['bar'], $actual[1]->input);
    }
}
