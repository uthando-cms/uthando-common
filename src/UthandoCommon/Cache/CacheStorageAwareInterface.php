<?php declare(strict_types=1);
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Cache
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Cache;

use Zend\Cache\Storage\Adapter\AbstractAdapter;

/**
 * Interface CacheStorageAwareInterface
 *
 * @package UthandoCommon\Cache
 */
interface CacheStorageAwareInterface
{
    /**
     * @return mixed
     */
    public function getCache(): ?AbstractAdapter;

    /**
     * @param AbstractAdapter $cache
     * @return mixed
     */
    public function setCache(AbstractAdapter $cache);
}
