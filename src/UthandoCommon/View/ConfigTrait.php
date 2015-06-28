<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\View
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */
namespace UthandoCommon\View;

use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\Exception\InvalidArgumentException;

/**
 * Class ConfigTrait
 * @package UthandoCommon\View
 * @method \Zend\View\HelperPluginManager getServiceLocator()
 */
trait ConfigTrait
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
     * @return array|null
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
     * @return AbstractViewHelper
     */
    protected function setConfig()
    {
        $this->config = $this->getServiceLocator()
            ->getServiceLocator()
            ->get('config');
        return $this;
    }
}
