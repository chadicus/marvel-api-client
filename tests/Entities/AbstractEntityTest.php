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
     * @covers ::__construct
     * @covers ::__call
     * @covers ::__get
     *
     * @return void
     */
    public function basicUsage()
    {
        $actual = SimpleEntity::fromArrays([['field' => 'foo'], ['field' => 'bar']]);
        $this->assertSame(2, count($actual));

        $this->assertSame('foo', $actual[0]->field);
        $this->assertSame('bar', $actual[1]->getField());
    }

    /**
     * Verify behavior of __get() with invalid property name.
     *
     * @test
     * @covers ::__get
     * @expectedException \Chadicus\Spl\Exceptions\UndefinedPropertyException
     * @expectedExceptionMessage Undefined Property Chadicus\Marvel\Api\Entities\SimpleEntity::$foo
     *
     * @return void
     */
    public function getUndefined()
    {
        (new SimpleEntity([]))->foo;
    }

    /**
     * Verify behavior of __call() with a non-get method call.
     *
     * @test
     * @covers ::__call
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Chadicus\Marvel\Api\Entities\SimpleEntity::doSomething() does not exist
     *
     * @return void
     */
    public function callNonGet()
    {
        (new SimpleEntity([]))->doSomething();
    }

    /**
     * Verify behavior of __call() with a un defined property.
     *
     * @test
     * @covers ::__call
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Chadicus\Marvel\Api\Entities\SimpleEntity::getFoo() does not exist
     *
     * @return void
     */
    public function callUndefined()
    {
        (new SimpleEntity([]))->getFoo();
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
        $actual = SimpleEntity::fromArray(['field' => 'foo']);
        $this->assertSame('foo', $actual->getField());
    }

}
