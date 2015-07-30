<?php
namespace Chadicus\Marvel\Api\Entities;

/**
 * Unit tests for the Price class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Price
 */
final class PriceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify invalid constructor parameters cause exceptions.
     *
     * @param mixed $type   The description of the amount.
     * @param mixed $amount The amount of the price.
     *
     * @test
     * @covers ::__construct
     * @dataProvider constructorBadData
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function constructWithInvalidParameters($type, $amount)
    {
        new Price($type, $amount);
    }

    /**
     * Data provider for constructWithInvalidParameters.
     *
     * @return array
     */
    public function constructorBadData()
    {
        return [
            'type is empty' => ['', 'a amount'],
            'type is whitespace' => ["\t \n", 'a amount'],
            'type is not a string' => [true, 'a amount'],
            'amount is not a float' => ['a type', false],
        ];
    }

    /**
     * Verify valid constructor parameters cause no exceptions.
     *
     * @param mixed $type   The description of the amount.
     * @param mixed $amount The amount of the price.
     *
     * @test
     * @covers ::__construct
     * @covers ::getAmount
     * @covers ::getType
     * @dataProvider constructorGoodData
     *
     * @return void
     */
    public function constructWithValidParameters($type, $amount)
    {
        $price = new Price($type, $amount);
        $this->assertSame($type, $price->getType());
        $this->assertSame($amount, $price->getAmount());
    }

    /**
     * Data provider for constructWithInvalidParameters.
     *
     * @return array
     */
    public function constructorGoodData()
    {
        return [
            'type is null, amount is null' => [null, null],
            'type is null, amount is float' => [null, 1.0],
            'type is string, amount is null' => ['a type', null],
            'type is string, amount is float' => ['a type', 1.0],
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
        $price = Price::fromArray(['type' => 'a type', 'amount' => 1.0]);
        $this->assertSame('a type', $price->getType());
        $this->assertSame(1.0, $price->getAmount());
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
        Price::fromArray(['type' => 'a type', 'amount' => 'amount not a float']);
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
        $prices = Price::fromArrays(
            [
                ['type' => 'a type', 'amount' => 1.0],
                ['type' => 'another type', 'amount' => 2.0],
            ]
        );

        $this->assertSame(2, count($prices));
        $this->assertSame('a type', $prices[0]->getType());
        $this->assertSame(1.0, $prices[0]->getAmount());
        $this->assertSame('another type', $prices[1]->getType());
        $this->assertSame(2.0, $prices[1]->getAmount());
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
        Price::fromArrays(
            [
                ['type' => 'a type', 'amount' => 1.0],
                ['type' => '', 'amount' => 2.0],
            ]
        );
    }
}
