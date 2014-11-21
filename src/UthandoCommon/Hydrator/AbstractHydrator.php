<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Hydrator
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */
namespace UthandoCommon\Hydrator;

use Zend\Stdlib\Hydrator\AbstractHydrator as ZendAbstractHydrator;

/**
 * Class AbstractHydrator
 * @package UthandoCommon\Hydrator
 */
abstract class AbstractHydrator extends ZendAbstractHydrator
{
	public function hydrate(array $data, $object)
	{
		foreach ($data as $key => $value) {
			if ($object->has($key)) {
				$method = 'set' . ucfirst($key);
				$value = $this->hydrateValue($key, $value, $data);
				$object->$method($value);
			}
		}
    	 
    	return $object;
	}
}
