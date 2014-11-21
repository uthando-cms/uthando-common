<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Stdlib
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */
namespace UthandoCommon\Stdlib;

/**
 * Class OptionsTrait
 * @package UthandoCommon\Stdlib
 */
trait OptionsTrait
{
    /**
     * @var object
     */
    protected $options;
    
    /**
     * get an option by name
     * 
     * @param string $name
     * @return mixed
     */
    public function getOption($name)
    {
        if (!$this->hasOption($name)) {
            return null;
        }
        
        $getter = 'get' . ucfirst($name);
        
        return $this->options->{$getter}();
    }
    
    /**
     * Check to see if option exists
     * 
     * @param string $prop
     * @return boolean
     */
    public function hasOption($prop)
    {
        $prop = (string) $prop;
        
        if (is_object($this->options)) {
            $getter = 'get' . ucfirst($prop);
            return method_exists($this->options, $getter);
        }
        
        return false;
        
    }
    
	/**
	 * @return $options
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * @param object|array $options
	 */
	public function setOptions($options)
	{
		$this->options = $options;
		return $this;
	}
}
