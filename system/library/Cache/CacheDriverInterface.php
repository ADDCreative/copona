<?php

namespace Copona\System\Library\Cache;

use Psr\SimpleCache\CacheInterface;

interface CacheDriverInterface extends CacheInterface
{
    public function __construct(array $configs = []);
}