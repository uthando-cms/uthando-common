<?php

namespace UthandoCommon\Mapper;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Exception\InvalidPluginException;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;

class MapperManager extends AbstractPluginManager
{
    /**
     * @var bool
     */
    protected $sqliteConstraints = false;

    /**
     * @param ConfigInterface $configuration
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);

        $this->addInitializer(array($this, 'injectDbAdapter'));
    }

    /**
     * @param $mapper
     * @return void
     */
    public function injectDbAdapter($mapper)
    {
        if ($mapper instanceof DbAdapterAwareInterface) {
            /* @var $dbAdapter Adapter */
            $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
            $config = $this->serviceLocator->get('config');

            // enable foreign key constraints on sqlite.
            if (isset($config['db']['sqlite_constraints']) && !$this->sqliteConstraints) {
                $dbAdapter->query('PRAGMA FOREIGN_KEYS = ON', Adapter::QUERY_MODE_EXECUTE);
                $this->sqliteConstraints = true;
            }

            $mapper->setDbAdapter($dbAdapter);
        }
    }

    /**
     * Validate the plugin
     *
     * Checks that the mapper is an instance of MapperInterface
     *
     * @param  mixed $plugin
     * @throws InvalidPluginException
     * @return void
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