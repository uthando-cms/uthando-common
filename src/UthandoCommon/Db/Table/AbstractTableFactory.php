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
     * @var array
     */
    protected $tableNamesMap;

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
        if (class_exists($requestedName)) {

            /* @var \Zend\Db\Adapter\Adapter $dbAdapter */
            $dbAdapter          = $serviceLocator->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = $this->getHydrator($requestedName);
            $tableGateway       = new TableGateway($this->tableNamesMap[$requestedName], $dbAdapter, null, $resultSetPrototype);
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
        $hydratorOrModel = str_replace('Db\Table', $hydratorOrModel, $requestedName);
        $hydratorOrModel = str_replace('Table', '', $hydratorOrModel);

        return (class_exists($hydratorOrModel)) ? new $hydratorOrModel() : null;
    }

    /**
     * @param $requestedName
     * @return null|object
     * @throws UthandoException
     */
    public function getModel($requestedName)
    {
        $model  = $this->getHydratorOrModel($requestedName, 'Model');

        if (null === $model) {
            throw new UthandoException('model class:' .$requestedName .' does not exist');
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
