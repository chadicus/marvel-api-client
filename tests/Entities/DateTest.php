<?php
namespace Chadicus\Marvel\Api\Entities;

/**
 * Unit tests for the Date class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Date
 * @covers ::__construct
 */
final class DateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify basic behavior of fromArray().
     *
     * @test
     * @covers ::fromArray
     * @covers ::getType
     * @covers ::getDate
     *
     * @return void
     */
    public function fromArray()
    {
        $now = new \DateTime();
        $input = [
            'type' => 'a type',
            'date' => $now->format('r'),
        ];

        $summary = Date::fromArray($input);

        $this->assertSame($input['type'], $summary->getType());
        $this->assertSame($input['date'], $summary->getDate()->format('r'));
    }
}
