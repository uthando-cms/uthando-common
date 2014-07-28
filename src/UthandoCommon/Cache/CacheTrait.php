<?php
namespace UthandoCommon\Cache;

use Zend\Cache\Storage\Adapter\AbstractAdapter;

trait CacheTrait
{
    /**
     * @var AbstractAdapter
     */
    protected $cache;
    
    public function getCacheItem($id)
    {
        $id = $this->getCacheKey($id);
        return $this->getCache()->getItem($id);
    }
    
    public function setCacheItem($id, $item)
    {
        $id = $this->getCacheKey($id);
        $this->getCache()->setItem($id, $item);
        return $this;
    }
    
    public function removeCacheItem($id)
    {
        $id = $this->getCacheKey($id);
        $this->getCache()->removeItem($id);
        return $this;
    }
    
    public function getCacheKey($id)
    {
        $id = (string) $id;
        $key = md5(get_class($this) . '-' . $id);
        return $key;
    }
    
    public function getCache()
    {
        return $this->cache;
    }
    
    public function setCache(AbstractAdapter $cache)
    {
        $this->cache = $cache;
        return $this;
    }
}
