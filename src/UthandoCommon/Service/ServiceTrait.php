<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Service
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Service;

/**
 * Class ServiceTrait
 *
 * @package UthandoCommon\Service
 * @method ServiceManager getServiceLocator()
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
     * @return object|AbstractService
     */
    protected function getService($service = null, $options = [])
    {
        $service = $service ?? $this->getServiceName();

        if (!isset($this->service[$service])) {
            $sl = $this->getServiceLocator()->get(ServiceManager::class);
            $this->service[$service] = $sl->get($service, $options);
        }

        return $this->service[$service];
    }
}
