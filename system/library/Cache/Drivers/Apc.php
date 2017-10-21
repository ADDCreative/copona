<?php

namespace Copona\System\Library\Cache\Drivers;

use Copona\System\Library\Cache\CacheDriverInterface;
use phpFastCache\Helper\Psr16Adapter;

class Apc implements CacheDriverInterface
{
    /**
     * @var Psr16Adapter
     */
    protected $adapter;

    public function __construct(array $configs = [])
    {
        $this->adapter = new Psr16Adapter('apc', $configs);
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