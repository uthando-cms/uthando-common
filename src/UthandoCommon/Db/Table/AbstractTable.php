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
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbTableGateway;
use Zend\Paginator\Paginator;

/**
 * Class AbstractTable
 *
 * @package CMS\Db\Table
 */
class AbstractTable
{
    /**
     * @var TableGateway
     */
    protected $tableGateway;

    /**
     * @var string|array
     */
    protected $primaryKey;

    /**
     * @var bool
     */
    protected $initialised = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setPrimaryKey('id');
    }

    /**
     * Get one row by it's id
     *
     * @param $id
     * @return array|\ArrayObject|ModelInterface|null
     */
    public function getById($id)
    {
        $id = (int) $id;
        $rowSet = $this->getTableGateway()->select([$this->getPrimaryKey() => $id]);
        $row = $rowSet->current();
        return $row;
    }

    /**
     * Fetch all records.
     *
     * @param bool $paginated
     * @return ResultSet
     */
    public function fetchAll($paginated=false)
    {
        if ($paginated) {
            $paginatorAdapter   = new DbTableGateway($this->tableGateway);
            $paginator          = new Paginator($paginatorAdapter);

            return $paginator;
        }
        
        $resultSet = $this->getTableGateway()->select();

        return $resultSet;
    }

    /**
     * @return TableGateway
     */
    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    /**
     * @param TableGateway $tableGateway
     * @return $this
     */
    public function setTableGateway($tableGateway)
    {
        $this->tableGateway = $tableGateway;
        return $this;
    }

    /**
     * Get the tables primary key
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        if (null === $this->primaryKey) {

        }
        return $this->primaryKey;
    }

    /**
     * Set the tables primary key
     *
     * @param string $primaryKey
     * @return $this
     */
    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
        return $this;
    }

    /**
     * @param BaseHydrator $hydrator
     * @param string $tableName
     * @return array
     */
    public function getColumnMapFromHydrator(BaseHydrator $hydrator, $tableName)
    {
        $columns    = $hydrator->getMap();
        $keys       = array_keys($columns);
        $values     = array_values(array_flip($columns));
        $columns    = array_combine($keys, $values);
        $columns    = array_flip(preg_filter('/^/', $tableName . '.', $columns));

        return $columns;
    }
}
