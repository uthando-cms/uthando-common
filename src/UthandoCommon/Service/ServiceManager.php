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

use Zend\EventManager\EventManagerAwareInterface;
use Zend\Mvc\Exception\InvalidPluginException;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager as ZendServiceManager;
use Zend\Stdlib\InitializableInterface;

/**
 * Class ModelManager
 * @package UthandoCommon\Servie
 */
class ServiceManager extends ZendServiceManager implements ServiceLocatorAwareInterface
{
    /**
     * The main service locator
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    protected $initialize = true;

    use ServiceLocatorAwareTrait;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config = null)
    {
        parent::__construct($config);

        $this->addInitializer([$this, 'callServiceInit']);
        $this->addInitializer([$this, 'callServiceEvents']);
        $this->addInitializer([$this, 'injectServiceLocator']);

    }

    /**
     * Sets up the class events.
     *
     * @param $service
     */
    public function callServiceEvents($service)
    {
        if ($service instanceof ServiceInterface) {
            $service->attachEvents();
        }
    }

    /**
     * Sets the service locator in service.
     *
     * @param $service
     */
    public function injectServiceLocator($service)
    {
        if ($service instanceof ServiceLocatorAwareInterface) {
            $service->setServiceLocator($this);
        }
    }

    /**
     * Calls the service init method.
     *
     * @param $service
     */
    public function callServiceInit($service)
    {
        if ($service instanceof InitializableInterface  && $this->initialize) {
            $service->init();
        }
    }

    /**
     * Retrieve a service from the manager by name
     *
     * Allows passing an array of options to use when creating the instance.
     * createFromInvokable() will use these and pass them to the instance
     * constructor if not null and a non-empty array.
     *
     * @param string $name
     * @param array $options
     * @param bool $usePeeringServiceManagers
     * @return object
     * @throws InvalidPluginException
     */
    public function get($name, $options = [], $usePeeringServiceManagers = true)
    {
        // If service not registered check the the Service Locator.
        if (!$this->has($name)) {
            return $this->getServiceLocator()->get($name);
        }

        if (isset($options['initialize'])) {
            $this->initialize = $options['initialize'];
        }

        $instance = parent::get($name, $usePeeringServiceManagers);
        $this->validateService($instance);
        return $instance;
    }

    /**
     * Register a service with the locator.
     *
     * Validates that the service object via validatePlugin() prior to
     * attempting to register it.
     *
     * @param  string $name
     * @param  mixed $service
     * @param  bool $shared
     * @return AbstractPluginManager
     * @throws \Zend\ServiceManager\Exception\InvalidArgumentException
     */
    public function setService($name, $service, $shared = true)
    {
        if ($service) {
            $this->validateService($service);
        }
        parent::setService($name, $service, $shared);
        return $this;
    }

    /**
     * Validate the plugin
     *
     * Checks that the Service is an instance of ServiceInterface
     *
     * @param  mixed $service
     * @throws InvalidPluginException
     * @return void
     */
    public function validateService($service)
    {
        if ($service instanceof ServiceInterface) {
            return;
        }

        throw new InvalidPluginException(sprintf(
            'Plugin of type %s is invalid; must implement %s\Service\ServiceInterface',
            (is_object($service) ? get_class($service) : gettype($service)),
            __NAMESPACE__
        ));
    }
} 