<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 25/09/17 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\Form\Element;


use UthandoCommon\Options\CacheOptions;
use Zend\Form\Element\Select;

class CacheAdapterSelect extends Select
{
    public function init()
    {
        $registeredAdapters = array_keys(CacheOptions::$adapterOptionsMap);

        $options = [];

        foreach ($registeredAdapters as $adapter) {
            $options[] = [
                'label' => $adapter,
                'value' => $adapter,
            ];
        }

        $this->setValueOptions($options);
    }
}
