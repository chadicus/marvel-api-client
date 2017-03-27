<?php
namespace Chadicus\Marvel\Api;

/**
 * Defines unit tests for the Filterer class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Filterer
 * @covers ::getFilterAliases
 */
final class FiltererTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Verify basic behavior of filter()
     *
     * @test
     * @covers ::filter
     *
     * @return void
     */
    public function filter()
    {
        $filters = [
            'foo' => [['string']],
            'date' => [['formatDate', 'Y-m-d']],
            'bool' => [['boolToString']],
        ];

        $now = new \DateTime();

        list($success, $filteredInput, $error) = Filterer::filter(
            $filters,
            [
                'foo' => 'bar',
                'date' => $now,
                'bool' => true,
            ]
        );
        $this->assertTrue($success);
        $this->assertSame('bar', $filteredInput['foo']);
        $this->assertSame($now->format('Y-m-d'), $filteredInput['date']);
        $this->assertSame('true', $filteredInput['bool']);
        $this->assertNull($error);
    }
}
