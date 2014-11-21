<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Hydrator\Strategy
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */
namespace UthandoCommon\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use DateTime as DateTimeClass;

/**
 * Class DateTime
 * @package UthandoCommon\Hydrator\Strategy
 */
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
