<?php
namespace UthandoCommon\Cache;

use Zend\Cache\Storage\Adapter\AbstractAdapter;

interface CacheStorageAwareInterface
{
    public function getCacheItem($id);
    public function setCacheItem($key, $item);
    public function removeCacheItem($id);
    public function getCacheKey($id);
    public function getCache();
    public function setCache(AbstractAdapter $cache);
}
