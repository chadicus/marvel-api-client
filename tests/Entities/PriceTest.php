<?php
namespace Chadicus\Marvel\Api\Entities;

/**
 * Unit tests for the Price class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Price
 * @covers ::<protected>
 */
final class PriceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify invalid constructor parameters cause exceptions.
     *
     * @param mixed $type   The description of the price.
     * @param mixed $price The price of the price.
     *
     * @test
     * @dataProvider constructorBadData
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function constructWithInvalidParameters($type, $price)
    {
        new Price(['type' => $type, 'price' => $price]);
    }

    /**
     * Data provider for constructWithInvalidParameters.
     *
     * @return array
     */
    public function constructorBadData()
    {
        return [
            'type is empty' => ['', 'a price'],
            'type is whitespace' => ["\t \n", 'a price'],
            'type is not a string' => [true, 'a price'],
            'price is not a float' => ['a type', false],
        ];
    }

    /**
     * Verify valid constructor parameters cause no exceptions.
     *
     * @param mixed $type   The description of the price.
     * @param mixed $price The price of the price.
     *
     * @test
     * @dataProvider constructorGoodData
     *
     * @return void
     */
    public function constructWithValidParameters($type, $price)
    {
        $entity = new Price(['type' => $type, 'price' => $price]);
        $this->assertSame($type, $entity->getType());
        $this->assertSame($price, $entity->getPrice());
    }

    /**
     * Data provider for constructWithInvalidParameters.
     *
     * @return array
     */
    public function constructorGoodData()
    {
        return [
            'type is null, price is null' => [null, null],
            'type is null, price is float' => [null, 1.0],
            'type is string, price is null' => ['a type', null],
            'type is string, price is float' => ['a type', 1.0],
        ];
    }

    /**
     * Verify basic behavior of fromArray().
     *
     * @test
     *
     * @return void
     */
    public function fromArray()
    {
        $price = Price::fromArray(['type' => 'a type', 'price' => 1.0]);
        $this->assertSame('a type', $price->getType());
        $this->assertSame(1.0, $price->getPrice());
    }

    /**
     * Verify fromArray throws when input is invalid.
     *
     * @test
     * @expectedException \Exception
     *
     * @return void
     */
    public function fromArrayWithInvalidInput()
    {
        Price::fromArray(['type' => 'a type', 'price' => 'price not a float']);
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
        $prices = Price::fromArrays(
            [
                ['type' => 'a type', 'price' => 1.0],
                ['type' => 'another type', 'price' => 2.0],
            ]
        );

        $this->assertSame(2, count($prices));
        $this->assertSame('a type', $prices[0]->getType());
        $this->assertSame(1.0, $prices[0]->getPrice());
        $this->assertSame('another type', $prices[1]->getType());
        $this->assertSame(2.0, $prices[1]->getPrice());
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
        Price::fromArrays(
            [
                ['type' => 'a type', 'price' => 1.0],
                ['type' => '', 'price' => 2.0],
            ]
        );
    }
}
