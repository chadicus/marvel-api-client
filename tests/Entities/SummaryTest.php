<?php
namespace Chadicus\Marvel\Api\Entities;

/**
 * Unit tests for the Summary class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Summary
 * @covers ::<protected>
 */
final class SummaryTest extends \PHPUnit\Framework\TestCase
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
        $input = [
            'resourceURI' => 'a resource url',
            'name' => 'a name',
            'type' => 'a type',
            'role' => 'a role',
        ];

        $summary = Summary::fromArray($input);

        $this->assertSame($input['resourceURI'], $summary->getResourceURI());
        $this->assertSame($input['name'], $summary->getName());
        $this->assertSame($input['type'], $summary->getType());
        $this->assertSame($input['role'], $summary->getRole());
    }
}
