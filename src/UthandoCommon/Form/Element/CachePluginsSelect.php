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


use Zend\Cache\Storage\Plugin\Serializer;
use Zend\Form\Element\Select;

class CachePluginsSelect extends Select
{
    public function init()
    {
        $options = [
            [
                'label' => Serializer::class,
                'value' => Serializer::class,
            ]
        ];

        $this->setValueOptions($options);
    }
}
