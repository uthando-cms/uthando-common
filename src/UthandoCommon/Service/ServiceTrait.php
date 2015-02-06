<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 * 
 * @package   UthandoCommon\Service
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Service;

/**
 * Class ServiceTrait
 * @package UthandoCommon\Service
 * @method \Zend\ServiceManager\ServiceLocatorInterface getServiceLocator()
 */
trait ServiceTrait
{
    /**
     * @var string
     */
    protected $serviceAlias;

    /**
     * @var array
     */
    protected $services = [];

    /**
     * @param $service
     * @return AbstractService
     */
    public function getService($service)
    {
        if (!array_key_exists($service, $this->services)) {
            $this->setService($service);
        }

        return $this->services[$service];
    }

    /**
     * @param $service
     * @return $this
     */
    public function setService($service)
    {
        $sl = $this->getServiceLocator();
        $this->services[$service] = $sl->get($service);

        return $this;
    }
} 