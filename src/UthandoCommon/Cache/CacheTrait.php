<?php
namespace UthandoCommon\Cache;

use Zend\Cache\Storage\Adapter\AbstractAdapter;
use Zend\Cache\Storage\TaggableInterface;

trait CacheTrait
{
    /**
     * @var AbstractAdapter
     */
    protected $cache;

    /**
     * @var bool
     */
    protected $useCache = true;
    
    /**
     * @var array
     */
    protected $tags;
    
    public function getCacheItem($id)
    {
        $id = $this->getCacheKey($id);
        return $this->getCache()->getItem($id);
    }
    
    public function setCacheItem($id, $item)
    {
        $id = $this->getCacheKey($id);
        $cache = $this->getCache();
        
        $cache->setItem($id, $item);
        
        if ($this->tags && $cache instanceof TaggableInterface) {
            $cache->setTags($id, $this->tags);
        }
        
        return $this;
    }
    
    public function removeCacheItem($id)
    {
        $id = $this->getCacheKey($id);
        $cache = $this->getCache();
        
        if ($this->tags && $cache instanceof TaggableInterface) {
            $cache->clearByTags($this->tags);
        }
        
        return $cache->removeItem($id);
    }
    
    /**
     * @param string $id
     * @return string
     */
    public function getCacheKey($id)
    {
        $id = (string) $id;
        $key = str_replace('\\', '-', get_class($this)) . '-' . md5($id);
        return $key;
    }
    
    /**
     * @return \Zend\Cache\Storage\Adapter\AbstractAdapter
     */
    public function getCache()
    {
        return $this->cache;
    }
    
    /**
     * @param AbstractAdapter $cache
     * @return \UthandoCommon\Cache\CacheTrait
     */
    public function setCache(AbstractAdapter $cache)
    {
        $this->cache = $cache;
        return $this;
    }
}
