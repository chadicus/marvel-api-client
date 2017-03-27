<?php
namespace Chadicus\Marvel\Api\Cache;

use Chadicus\Marvel\Api\Request;
use Chadicus\Marvel\Api\Response;

/**
 * Defines unit tests for the AbstractCache class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Cache\AbstractCache
 * @covers ::<protected>
 */
final class AbstractCacheTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Verify basic functionality of getDefaultTTL and setDefaultTTL
     *
     * @test
     * @covers ::getDefaultTTL
     * @covers ::setDefaultTTL
     *
     * @return void
     */
    public function defaultTTL()
    {
        $mock = $this->getMockForAbstractClass('\Chadicus\Marvel\Api\Cache\AbstractCache');
        $mock->setDefaultTTL(30);
        $this->assertSame(30, $mock->getDefaultTTL());
    }

    /**
     * Verify basic functionality of setDefaultTTL with invalid TTL
     *
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage TTL value must be an integer >= 1 and <= 86400
     *
     * @return void
     */
    public function setDefaultTTLGreaterThanMax()
    {
        $mock = $this->getMockForAbstractClass('\Chadicus\Marvel\Api\Cache\AbstractCache');
        $mock->setDefaultTTL(CacheInterface::MAX_TTL + 1);
    }
}
