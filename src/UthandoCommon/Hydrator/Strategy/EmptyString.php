<?php
namespace UthandoCommon\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class EmptyString implements StrategyInterface
{

	public function extract($value)
	{
		return $value;
	}
	
	public function hydrate($value)
	{
		return '';
	}

}
