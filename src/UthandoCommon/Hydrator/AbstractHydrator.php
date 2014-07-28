<?php
namespace UthandoCommon\Hydrator;

use Zend\Stdlib\Hydrator\AbstractHydrator as ZendAbstractHydrator;

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
