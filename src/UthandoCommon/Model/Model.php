<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Model
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */
namespace UthandoCommon\Model;

/**
 * Class Model
 * @package UthandoCommon\Model
 */
trait Model
{	
    /**
     * Check to see if this class has a getter method defined
     * 
     * @param string $prop
     * @return boolean
     */
	public function has($prop)
	{
		$getter = 'get' . ucfirst($prop);
		return method_exists($this, $getter);
	}
	
	/**
	 * Returns object properties as an array
	 * 
	 * @return array:
	 */
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
