<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Mapper
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Mapper;

use Zend\Db\Adapter\Adapter;

/**
 * Interface DbAdapterAwareInterface
 *
 * @package UthandoCommon\Mapper
 */
interface DbAdapterAwareInterface
{
    /**
     * @return Adapter
     */
    public function getAdapter();

    /**
     * @param Adapter $dbAdapter
     * @return $this
     */
    public function setDbAdapter(Adapter $dbAdapter);
}
