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
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
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
    public function fetchAll($paginated = false)
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
     * @param array $post
     * @param bool $paginated
     * @return null|\Zend\Db\ResultSet\ResultSetInterface|Paginator
     */
    public function search(array $post, $paginated = false)
    {
        $sort = (isset($post['sort'])) ? $post['sort'] : '';
        unset($post['sort'], $post['count'], $post['offset'], $post['page']);

        $searches = [];

        foreach ($post as $key => $value) {
            $searches[] = [
                'search-string' => $value,
                'columns' => explode('-', $key),
            ];
        }

        $select = $this->getTableGateway()->getSql()->select();

        foreach ($searches as $key => $value) {
            if (!$value['search-string'] == '') {
                if (substr($value['search-string'], 0, 1) == '=' && $key == 0) {
                    $id = (int)substr($value['search-string'], 1);
                    $select->where->equalTo($this->getPrimaryKey(), $id);
                } else {
                    $where = $select->where->nest();
                    $c = 0;

                    foreach ($value['columns'] as $column) {
                        if ($c > 0) $where->or;
                        $where->like($column, '%' . $value['search-string'] . '%');
                        $c++;
                    }

                    $where->unnest();
                }
            }
        }

        $select = $this->setSortOrder($select, $sort);

        if ($paginated) {
            $paginatorAdapter   = new DbSelect($select, $this->getTableGateway()->getAdapter(), $this->getTableGateway()->getResultSetPrototype());
            $paginator          = new Paginator($paginatorAdapter);

            return $paginator;
        }

        $resultSet = $this->getTableGateway()->selectWith($select);

        return $resultSet;
    }

    /**
     * @param Select $select
     * @param $count
     * @param $offset
     * @return Select
     */
    public function setLimit(Select $select, $count, $offset)
    {
        if ($count === null) {
            return $select;
        }

        return $select->limit($count)
            ->offset($offset);
    }

    /**
     * Sets sort order of database query
     *
     * @param Select $select
     * @param string|array $sort
     * @return Select
     */
    public function setSortOrder(Select $select, $sort)
    {
        if ($sort === '' || null === $sort || empty($sort)) {
            return $select;
        }

        $select->reset('order');

        if (is_string($sort)) {
            $sort = explode(' ', $sort);
        }

        $order = [];

        foreach ($sort as $column) {
            if (strchr($column, '-')) {
                $column = substr($column, 1, strlen($column));
                $direction = Select::ORDER_DESCENDING;
            } else {
                $direction = Select::ORDER_ASCENDING;
            }

            $order[] = $column . ' ' . $direction;
        }

        return $select->order($order);
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
