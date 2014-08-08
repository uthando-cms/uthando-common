<?php
namespace UthandoCommon\Mapper;

use UthandoCommon\Model\ModelInterface;
use UthandoCommon\Mapper\DbAdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;

class AbstractMapper implements DbAdapterAwareInterface
{
    use AdapterAwareTrait;
    
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
	 * name of model class
	 *
	 * @var string
	 */
	protected $model;
	
	/**
	 * @var Sql
	 */
	protected $sql;
	
	/**
	 * @var string
	 */
	protected $hydrator;
	
	/**
	 * @var HydratingResultSet
	 */
	protected $resultSetProtype;
	
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
	 * @param string $tableName
	 * @return Select
	 */
	public function getSelect($tableName=null)
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
		if (!$this->resultSetProtype instanceof HydratingResultSet) {
			$resultSetPrototype = new HydratingResultSet;
			$resultSetPrototype->setHydrator($this->getHydrator());
			$resultSetPrototype->setObjectPrototype(new $this->model());
			$this->resultSetProtype = $resultSetPrototype;
		}
	
		return clone $this->resultSetProtype;
	}
	
	/**
	 * Gets one row by its id
	 *
	 * @param int $id
	 * @return AbstractModel|null
	 */
	public function getById($id)
	{
		$select = $this->getSelect()->where([$this->getPrimaryKey() => $id]);
		$rowset = $this->fetchResult($select);
		$row = $rowset->current();
		return $row;
	}
	
	/**
	 * Fetches all rows from database table.
	 *
	 * @return ResultSet
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
	 * @param string|array|Where $where
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
	 * @param string|array|Where $where
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
	 * @return \Application\Mapper\AbstractMapper
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
	 * Paginates the resultset
	 *
	 * @param ResultSet $resultSet
	 * @param int $page
	 * @param int $limit
	 * @return Paginator
	 */
	public function paginate(Select $select, $resultSet=null)
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
	 * @return \Zend\Db\ResultSet\ResultSet|Paginator|HydratingResultSet
	 */
	protected function fetchResult(Select $select, $resultSet=null)
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
	 * 
	 * @param AbstractModel|array $dataOrModel
	 * @param HydratorInterface $hydrator
	 * @throws \InvalidArgumentException
	 * @return array
	 */
	public function extract($dataOrModel, HydratorInterface $hydrator = null)
	{
		if (is_array($dataOrModel)) {
			return $dataOrModel;
		}
		
		if (!$dataOrModel instanceOf ModelInterface) {
			throw new \InvalidArgumentException('need instance of AbstractModel or AbstractOptions  got: ' . getType($dataOrModel));
		}
		
		$hydrator = $hydrator ?: $this->getHydrator();
		
		return $hydrator->extract($dataOrModel);
	}
	
	/**
	 * @return ClassMethods
	 */
	public function getHydrator()
	{
		if (is_string($this->hydrator) && class_exists($this->hydrator)) {
			return new $this->hydrator();
		} else {
			return new ClassMethods();
		}
	}
	
	/**
	 * @param array $data
	 * @return model
	 */
	public function getModel(array $data = null)
	{
		if (is_string($this->model) && class_exists($this->model)) {
			if ($data) {
				$hydrator = $this->getHydrator();
				return $hydrator->hydrate($data, new $this->model);
			}
			return new $this->model;
		} else {
			throw new \RuntimeException('could not instantiate model - ' . $this->model);
		}
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
     * @return $primary
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
