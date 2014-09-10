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
        $key = str_replace('\\', '-', get_class($this)) . '-' . md5($id);
        return $key;
    }
    
    public function getCache()
    {
        return $this->cache;
    }
    
    public function setCache(AbstractAdapter $cache)
    {
        $reflector = new \ReflectionClass($this);

        $options = $cache->getOptions();
        $options->setNamespace(str_replace('\\', '-', $reflector->getNamespaceName()));
        $options->setNamespaceSeparator(':');

        $cache->setOptions($options);

        $this->cache = $cache;

        return $this;
    }
}
