<?php

namespace Chadicus\Marvel\Api\Cache;

abstract class AbstractCache implements CacheInterface
{
    /**
     * Default time to live in seconds.
     *
     * @var integer
     */
    private $defaultTimeToLive = CacheInterface::MAX_TTL;

    /**
     * Sets the default time to live in seconds.
     *
     * @param integer $defaultTimeToLive The time in seconds
     *
     * @return void
     */
    final public function setDefaultTTL($defaultTimeToLive)
    {
        $this->defaultTimeToLive = self::ensureTTL($defaultTimeToLive);
    }

    /**
     * Returns the default time to live in seconds.
     *
     * @return integer The time in seconds
     */
    final public function getDefaultTTL()
    {
        return $this->defaultTimeToLive;
    }

    /**
     * Helper method to check TTL value
     *
     * @param integer $ttl The time value to check in seconds
     *
     * @return integer The valid $ttl value
     *
     * @throws \InvalidArgumentException Thrown if $ttl is < 1 or > CacheInterface::MAX_TTL
     */
    final protected static function ensureTTL($ttl)
    {
        if ($ttl < 1 || $ttl > CacheInterface::MAX_TTL) {
            throw new \InvalidArgumentException('TTL value must be an integer >= 1 and <= ' . CacheInterface::MAX_TTL);
        }

        return $ttl;
    }
}
