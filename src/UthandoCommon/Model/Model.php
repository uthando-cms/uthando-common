<?php
namespace UthandoCommon\Model;

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
