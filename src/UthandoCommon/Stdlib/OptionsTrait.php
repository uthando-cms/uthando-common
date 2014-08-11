<?php
namespace UthandoCommon\Stdlib;

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
