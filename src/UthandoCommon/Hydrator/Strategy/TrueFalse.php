<?php
namespace UthandoCommon\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class TrueFalse implements StrategyInterface
{

	public function extract($value)
	{
		return ($value == true) ? 1 : 0;
	}

	public function hydrate($value)
	{
		return ($value == 1) ? true : false;
	}
}
