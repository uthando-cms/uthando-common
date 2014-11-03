<?php
namespace UthandoCommon\Mapper;

use UthandoCommon\Model\ModelAwareInterface;
use UthandoCommon\Model\ModelAwareTrait;
use UthandoCommon\Model\ModelInterface;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\ResultSet\AbstractResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;

class AbstractDbMapper implements
    MapperInterface,
    DbAdapterAwareInterface,
    HydratorAwareInterface,
    ModelAwareInterface
{
    use AdapterAwareTrait,
        ModelAwareTrait;
    
	/**
	 * Name of table
	 *
	 * @var string
	 */
	protected $table;
	
	/**
	 * name of primary column
	 *
	 * @var string
	 */
	protected $primary;
	
	/**
	 * @var Sql
	 */
	protected $sql;
	
	/**
	 * @var HydratingResultSet
	 */
	protected $resultSetPrototype;
	
	/**
	 * @var boolean
	 */
	protected $usePaginator;
	
	/**
	 * @var array
	 */
	protected $paginatorOptions;
	
	/**
	 * return an instance of Select
	 * 
	 * @param string|null $tableName
	 * @return Select
	 */
	public function getSelect($tableName = null)
	{
		return $this->getSql()->select($tableName ?: $this->getTable());
	}
	
	/**
	 * gets the resultSet
	 *
	 * @return HydratingResultSet
	 */
	protected function getResultSet()
	{
		if (!$this->resultSetPrototype instanceof HydratingResultSet) {
			$resultSetPrototype = new HydratingResultSet;
			$resultSetPrototype->setHydrator($this->getHydrator());
			$resultSetPrototype->setObjectPrototype(new $this->model());
			$this->resultSetPrototype = $resultSetPrototype;
		}
	
		return clone $this->resultSetPrototype;
	}

    /**
     * Gets one or more rows by its id
     *
     * @param $id
     * @return array|ModelInterface
     */
    public function getById($id)
	{
        $select = $this->getSelect()->where([$this->getPrimaryKey() => $id]);
        $resultSet = $this->fetchResult($select);

        if ($resultSet->count() > 1) {
            $rowSet = [];
            foreach ($resultSet as $row) {
                $rowSet[] = $row;
            }
        } elseif ($resultSet->count() === 1) {
            $rowSet = $resultSet->current();
        } else {
            $rowSet = $this->getModel();
        }

        return $rowSet;
	}

    /**
     * Fetches all rows from database table.
     *
     * @return HydratingResultSet|\Zend\Db\ResultSet\ResultSet|Paginator
     */
    public function fetchAll()
	{
		$select = $this->getSelect();
		$resultSet = $this->fetchResult($select);
		
		return $resultSet;
	}
	
	/**
	 * basic search on table data
	 * 
	 * @param array $search
	 * @param string $sort
	 * @param Select $select
	 * @return \Zend\Db\ResultSet\ResultSet|Paginator|HydratingResultSet
	 */
	public function search(array $search, $sort, Select $select = null)
	{
		$select = ($select) ?: $this->getSelect();
		
		foreach ($search as $key => $value) {
			if (!$value['searchString'] == '') {
				if (substr($value['searchString'], 0, 1) == '=' && $key == 0) {
					$id = (int) substr($value['searchString'], 1);
					$select->where->equalTo($this->getPrimaryKey(), $id);
				} else {
					$where = $select->where->nest();
					$c = 0;
					
					foreach ($value['columns'] as $column) {
						if ($c > 0) $where->or;
						$where->like($column, '%' . $value['searchString'] . '%');
						$c++;
					}
			
					$where->unnest();
				}
			}
		}
		
		$select = $this->setSortOrder($select, $sort);
		
		return $this->fetchResult($select);
	}
	
	/**
	 * Inserts a new row into database returns insertId
	 *
	 * @param array $data
	 * @param string $table
	 * @return int|null
	 */
	public function insert(array $data, $table = null)
	{
		$table = ($table) ?: $this->getTable();
		$sql = $this->getSql();
		$insert = $sql->insert($table);
	
		$insert->values($data);
	
		$statement = $sql->prepareStatementForSqlObject($insert);
		$result = $statement->execute();
	
		return $result->getGeneratedValue();
	}
	
	/**
	 * Updates a database row/s.
	 *
	 * @param array $data
	 * @param string|array|\Zend\Db\Sql\Where $where
	 * @param string $table
	 * @return int number of affected rows
	 */
	public function update(array $data, $where, $table = null)
	{
		$table = ($table) ?: $this->getTable();
		$sql = $this->getSql();
		$update = $sql->update($table);
	
		$update->set($data)
			->where($where);
	
		$statement = $sql->prepareStatementForSqlObject($update);
	
		return $statement->execute();
	}
	
	/**
	 * Deletes a row/s in the database returns number
	 * of rows affacted
	 *
	 * @param string|array|\Zend\Db\Sql\Where $where
	 * @param string $table
	 * @return int
	 */
	public function delete($where, $table = null)
	{
		$table = ($table) ?: $this->getTable();
		$sql = $this->getSql();
		$delete = $sql->delete($table);
	
		$delete->where($where);
	
		$statement = $sql->prepareStatementForSqlObject($delete);
	
		return $statement->execute();
	}
	
	/**
	 * @param array $paginatorOptions
	 * @return \UthandoCommon\Mapper\AbstractMapper
	 */
	public function usePaginator(array $paginatorOptions)
	{
		$this->usePaginator = true;
		$this->setPaginatorOptions($paginatorOptions);
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function getPaginatorOptions()
	{
		return $this->paginatorOptions;
	}

	/**
	 * @param array $paginatorOptions
	 * @return \UthandoCommon\Mapper\AbstractMapper
	 */
	public function setPaginatorOptions(array $paginatorOptions)
	{
		$this->paginatorOptions = $paginatorOptions;
		return $this;
	}

    /**
     * Paginates the result set
     *
     * @param Select $select
     * @param AbstractResultSet $resultSet
     * @return Paginator
     */
    public function paginate(Select $select, AbstractResultSet $resultSet=null)
	{
		$resultSet = $resultSet ?: $this->getResultSet();
		$adapter = new DbSelect($select, $this->getAdapter(), $resultSet);
		$paginator = new Paginator($adapter);
		
		$options = $this->getPaginatorOptions();
		
		if (isset($options['limit'])) {
		    $paginator->setItemCountPerPage($options['limit']);
		}
		
		if (isset($options['page'])) {
		    $paginator->setCurrentPageNumber($options['page']);
		}
	
		$paginator->setPageRange(5);
	
		return $paginator;
	}
	
	/**
	 * Fetches the result of select from database
	 *
	 * @param Select $select
     * @param AbstractResultSet $resultSet
	 * @return \Zend\Db\ResultSet\ResultSet|Paginator|HydratingResultSet
	 */
	protected function fetchResult(Select $select, AbstractResultSet $resultSet=null)
	{
		$resultSet = $resultSet ?: $this->getResultSet();
		$resultSet->buffer();
		
		if($this->usePaginator) {
			$this->usePaginator = false;
			$resultSet = $this->paginate($select, $resultSet);
		} else {
            $statement = $this->getSql()->prepareStatementForSqlObject($select);
            $result = $statement->execute();
            $resultSet->initialize($result);
		}
	
		return $resultSet;
	}
	
	/**
	 * Sets database query limit
	 *
	 * @param Select $select
	 * @param int $count
	 * @param int $offset
	 * @return Select
	 */
	public function setLimit(Select $select, $count, $offset)
	{
		if ($count === null) {
			return $select;
		}
	
		return $select->limit($count, $offset);
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
		if ($sort === '' || null === $sort) {
			return $select;
		}
		
		$select->reset('order');
		
		if (is_string($sort)) {
			$sort = explode(' ', $sort);
		}
		
		$order = [];
	
		foreach ($sort as $column) {
			if (strchr($column,'-')) {
				$column = substr($column, 1, strlen($column));
				$direction = Select::ORDER_DESCENDING;
			} else {
				$direction = Select::ORDER_ASCENDING;
			}
			$order[] = $column. ' ' . $direction;
		}
	
		return $select->order($order);
	}
	
	/**
	 * @return Sql
	 */
	protected function getSql()
	{
		if (!$this->sql) {
			$this->sql = new Sql($this->getAdapter());
		}
	
		return $this->sql;
	}
	
	/**
	 * @return \Zend\Db\Adapter\Adapter
	 */
    public function getAdapter()
    {   
        return $this->adapter;
    }
    
    /**
     * @return string
     */
    public function getTable()
    {
    	return $this->table;
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
    	return $this->primary;
    }
    
    /**
     * @param Select $select
     * @return string $sqlString
     */
    public function getSqlString(Select $select)
    {
    	$adapterPlatform	= $this->getAdapter()->getPlatform();
    	$sqlString			= $select->getSqlString($adapterPlatform);
    	
    	return $sqlString;
    }
}