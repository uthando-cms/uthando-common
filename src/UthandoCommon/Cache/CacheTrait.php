<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Cache
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Cache;

use Zend\Cache\Storage\Adapter\AbstractAdapter;
use Zend\Cache\Storage\TaggableInterface;

/**
 * Class CacheTrait
 *
 * @package UthandoCommon\Cache
 */
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

    /**
     * @param $id
     * @return mixed|null
     */
    public function getCacheItem($id)
    {
        if (!$this->isUseCache()) return;

        $id = $this->getCacheKey($id);

        return $this->getCache()->getItem($id);
    }

    /**
     * @param $id
     * @param $item
     * @return $this
     */
    public function setCacheItem($id, $item)
    {
        if (!$this->isUseCache()) return $this;

        $id = $this->getCacheKey($id);
        $cache = $this->getCache();

        $cache->setItem($id, $item);

        if ($this->tags && $cache instanceof TaggableInterface) {
            $cache->setTags($id, $this->tags);
        }

        return $this;
    }

    /**
     * @param $id
     * @return bool|null
     */
    public function removeCacheItem($id)
    {
        if (!$this->isUseCache()) return;

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

    public function isUseCache()
    {
        return ($this->cache instanceof AbstractAdapter) ? $this->useCache : false;
    }

    /**
     * @param $useCache
     * @return $this
     */
    public function setUseCache($useCache)
    {
        $this->useCache = (bool) $useCache;
        return $this;
    }
}
