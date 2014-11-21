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

/**
 * Class TrueFalse
 * @package UthandoCommon\Hydrator\Strategy
 */
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
