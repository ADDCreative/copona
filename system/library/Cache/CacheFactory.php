<?php

namespace Copona\System\Library\Cache;

class CacheFactory
{
    public static function create($driver)
    {
        $configs = \Config::get('cache.configs', []);
        $adaptor = new $driver($configs);

        if (($adaptor instanceof CacheDriverInterface) == false) {
            throw new \RuntimeException($driver . ' not instance of ' . CacheDriverInterface::class);
        }

        return $adaptor;
    }
}