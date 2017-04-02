<?php

namespace Chadicus\Marvel\Api\Cache;

use Psr\SimpleCache;

class InvalidArgumentException extends \Exception implements SimpleCache\InvalidArgumentException
{
}
