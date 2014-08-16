<?php

namespace UthandoCommon\View;

use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\Exception\InvalidArgumentException;

/**
 * View Helper
 */
trait ViewHelperConfigTrait
{
    use ServiceLocatorAwareTrait;
    
    /**
     * @var array
     */
    protected $config;
    
    /**
     * Gets the config options as an array, if a key is supplied then that keys options is returned.
     * 
     * @param string $key
     * @throws array|InvalidArgumentException
     */
    protected function getConfig($key=null)
    {
        if ($this->config === null) {
            $this->setConfig();
        }
        
        if (null === $key) {
            return $this->config;
        }
        
        if (!array_key_exists($key, $this->config)) {
            throw new InvalidArgumentException("key: '" . $key . "' is not set in configuration options.");
        }
        
        return $this->config[$key];
    }
    
    /**
     * Sets the config array.
     * 
     * @return \Application\View\AbstractHelper
     */
    protected function setConfig()
    {
        $this->config = $this->serviceLocator->getServiceLocator()
            ->get('config');
        return $this;
    }
}
