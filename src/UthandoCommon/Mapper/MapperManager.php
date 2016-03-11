<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Mapper
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Mapper;

use UthandoCommon\Model\ModelAwareInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Exception\InvalidPluginException;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorAwareInterface;

/**
 * Class MapperManager
 *
 * @package UthandoCommon\Mapper
 */
class MapperManager extends AbstractPluginManager
{
    /**
     * @var bool
     */
    protected $sqliteConstraints = false;

    /**
     * @param null $configOrContainerInstance
     * @param array $v3config
     */
    public function __construct($configOrContainerInstance = null, array $v3config = [])
    {
        parent::__construct($configOrContainerInstance, $v3config);

        $this->addInitializer([$this, 'injectDbAdapter']);
        $this->addInitializer([$this, 'injectHydrator']);
        $this->addInitializer([$this, 'injectModel']);
    }

    /**
     * @param $mapper
     */
    public function injectDbAdapter($mapper)
    {
        if ($mapper instanceof DbAdapterAwareInterface) {
            /* @var $dbAdapter Adapter */
            $dbAdapter = (isset($this->creationOptions['dbAdapter'])) ? $this->creationOptions['dbAdapter'] :
                $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
            $config = $this->serviceLocator->get('config');

            // enable foreign key constraints on sqlite.
            if (isset($config['db']['sqlite_constraints']) && $config['db']['sqlite_constraints'] && !$this->sqliteConstraints) {
                $dbAdapter->query('PRAGMA FOREIGN_KEYS = ON', Adapter::QUERY_MODE_EXECUTE);
                $this->sqliteConstraints = true;
            }

            $mapper->setDbAdapter($dbAdapter);
        }
    }

    /**
     * @param $mapper
     */
    public function injectHydrator($mapper)
    {
        if ($mapper instanceof HydratorAwareInterface) {
            if (isset($this->creationOptions['hydrator'])) {
                $hydratorManager = $this->serviceLocator->get('HydratorManager');
                $mapper->setHydrator($hydratorManager->get($this->creationOptions['hydrator']));
            } else {
                $mapper->setHydrator(new ClassMethods());
            }
        }
    }

    /**
     * @param $mapper
     */
    public function injectModel($mapper)
    {
        if ($mapper instanceof ModelAwareInterface) {
            if (isset($this->creationOptions['model'])) {
                $modelManager = $this->serviceLocator->get('UthandoModelManager');
                $mapper->setModel($modelManager->get($this->creationOptions['model']));
            }
        }
    }

    /**
     * Validate the plugin
     *
     * Checks that the mapper is an instance of MapperInterface
     *
     * @param  mixed $plugin
     * @throws InvalidPluginException
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof MapperInterface) {
            return;
        }

        throw new InvalidPluginException(sprintf(
            'Plugin of type %s is invalid; must implement %s\Mapper\MapperInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}
