<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 * 
 * @package   UthandoCommon\Controller
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ServiceTrait
 * @package UthandoCommon\Controller
 * @method ServiceLocatorInterface getServiceLocator()
 */
trait ServiceTrait
{
    /**
     * @var string
     */
    protected $serviceName;

    /**
     * @var array
     */
    protected $service = [];

    /**
     * @return string
     */
    protected function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @param string $serviceName
     * @return $this
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    /**
     * @param null $service
     * @param array $options
     * @return mixed
     */
    protected function getService($service = null, $options = [])
    {
        $service = (is_string($service)) ? $service : $this->getServiceName();

        if (!isset($this->service[$service])) {
            $sl = $this->getServiceLocator()->get('UthandoServiceManager');
            $this->service[$service] = $sl->get($service, $options);
        }

        return $this->service[$service];
    }
} 