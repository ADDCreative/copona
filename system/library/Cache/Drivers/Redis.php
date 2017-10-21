<?php

namespace Copona\System\Library\Cache\Drivers;

use Copona\System\Library\Cache\CacheDriverInterface;
use phpFastCache\CacheManager;
use phpFastCache\Helper\Psr16Adapter;

class Redis implements CacheDriverInterface
{
    /**
     * @var Psr16Adapter
     */
    protected $adapter;

    public function __construct(array $configs = [])
    {
        $redis = CacheManager::Redis($configs);
        $this->adapter = new Psr16Adapter($redis);
    }

    public function get($key, $default = null)
    {
        return $this->adapter->get($key, $default);
    }

    public function set($key, $value, $ttl = null)
    {
        $this->adapter->set($key, $value, $ttl);
    }

    public function delete($key)
    {
        $this->adapter->delete($key);
    }

    public function clear()
    {
        $this->adapter->clear();
    }

    public function getMultiple($keys, $default = null)
    {
        return $this->adapter->getMultiple($keys, $default);
    }

    public function setMultiple($values, $ttl = null)
    {
        return $this->adapter->setMultiple($values, $ttl);
    }

    public function deleteMultiple($keys)
    {
        $this->adapter->deleteMultiple($keys);
    }

    public function has($key)
    {
        $this->adapter->has($key);
    }
}