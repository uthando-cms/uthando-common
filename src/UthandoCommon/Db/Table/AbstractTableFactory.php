<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Db\Table
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2015 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Db\Table;

use UthandoCommon\Hydrator\BaseHydrator;
use UthandoCommon\Model\ModelInterface;
use UthandoCommon\UthandoException;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractTableFactory
 *
 * @package UthandoCommon\Db\Table
 */
class AbstractTableFactory implements AbstractFactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return (fnmatch('*Table', $requestedName)) ? true : false;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $serviceLocator->get('config');
        $tableNamesMap = (isset($config['db_table_names_map'])) ? $config['db_table_names_map'] : null;

        if (class_exists($requestedName) && is_array($tableNamesMap) && array_key_exists($requestedName, $tableNamesMap)) {

            /* @var \Zend\Db\Adapter\Adapter $dbAdapter */
            $dbAdapter          = $serviceLocator->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = $this->getHydrator($requestedName);
            $tableGateway       = new TableGateway($tableNamesMap[$requestedName], $dbAdapter, null, $resultSetPrototype);
            /* @var AbstractTable $table */
            $table              = new $requestedName();

            $table->setTableGateway($tableGateway);

            return $table;
        }

        return false;
    }

    /**
     * @param $requestedName
     * @param $hydratorOrModel
     * @return null|BaseHydrator|ModelInterface
     */
    public function getHydratorOrModel($requestedName, $hydratorOrModel)
    {
        $class = str_replace('Db\Table', $hydratorOrModel, $requestedName);
        $class = str_replace('Table', $hydratorOrModel, $class);

        return (class_exists($class)) ? new $class() : null;
    }

    /**
     * @param $requestedName
     * @return null|object
     * @throws UthandoException
     */
    public function getModel($requestedName)
    {
        $model  = $this->getHydratorOrModel($requestedName, 'Entity');

        if (null === $model) {
            throw new UthandoException('Entity class:' .$requestedName .' does not exist');
        }

        return $model;
    }

    /**
     * @param $requestedName
     * @return HydratingResultSet|ResultSet
     */
    public function getHydrator($requestedName)
    {
        $hydrator  = $this->getHydratorOrModel($requestedName, 'Hydrator');

        if ($hydrator) {
            $resultSetPrototype = new HydratingResultSet();
            $resultSetPrototype->setHydrator($hydrator);
            $resultSetPrototype->setObjectPrototype($this->getModel($requestedName));
        } else {
            $resultSetPrototype = new ResultSet();
        }

        return $resultSetPrototype;

    }
}
