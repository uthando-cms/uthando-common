<?php
namespace UthandoCommon\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use DateTime as DateTimeClass;

class DateTime implements StrategyInterface
{
	protected $dateFormat = 'Y-m-d H:i:s';
	
	public function extract($value)
	{
		if (!$value instanceof DateTimeClass) {
			$value = new DateTimeClass();
		}
		
		return $value->format($this->dateFormat);
	}

	public function hydrate($value)
	{
		if (is_string($value) && '' === $value) {
			$value = null;
		} else {
			$value = new DateTimeClass($value);
		}
		
		return $value;
	}
}
