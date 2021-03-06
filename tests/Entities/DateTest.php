<?php
namespace Chadicus\Marvel\Api\Entities;

/**
 * Unit tests for the Date class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Date
 * @covers ::<protected>
 */
final class DateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Verify basic behavior of fromArray().
     *
     * @test
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
