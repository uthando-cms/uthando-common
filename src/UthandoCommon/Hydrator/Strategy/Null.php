<?php

namespace UthandoCommon\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class Null implements StrategyInterface
{
	public function extract($value)
	{
		return (0 == $value || '' == $value) ? null : $value;
	}
	
	public function hydrate($value)
	{
		return (0 == $value || '' == $value) ? null : $value;
	}
}
