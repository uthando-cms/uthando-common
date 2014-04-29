<?php

namespace UthandoCommon\Mapper;

use UthandoCommon\Mapper\AbstractMapper;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Expression;

abstract class AbstractNestedSet extends AbstractMapper
{
	const INSERT_NODE	= 'insert';
	const INSERT_CHILD	= 'insertSub';
	const COLUMN_LEFT	= 'lft';
	const COLUMN_RIGHT	= 'rgt';
	
    /**
     * Gets all items in tree.
     * 
     * @see \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll()
    {
        $select = $this->getFullTree();
                	
        return $this->fetchResult($select);
    }
    
    /**
     * Get only the top level items in tree.
     * 
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchTopLevelOnly()
    {
        $select = $this->getFullTree();
        $select->having('depth = 0');
        	
        return $this->fetchResult($select);
    }
    
    /**
     * Gets the full tree from database
     * 
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getFullTree()
    {   
        $select = $this->getSql()->select();
        $select->from(['child' => $this->getTable()])
            ->columns([
                Select::SQL_STAR,
                'depth' => new Expression('(COUNT(parent.'.$this->getPrimaryKey().') - 1)')
            ])
            ->join(
                ['parent' => $this->getTable()],
                'child.' . self::COLUMN_LEFT . ' BETWEEN parent.' . self::COLUMN_LEFT . ' AND parent.' . self::COLUMN_RIGHT,
                [],
                Select::JOIN_INNER
            )
            ->group('child.' . $this->getPrimaryKey())
            ->order('child.' . self::COLUMN_LEFT);
		
        return $select;
    }

    /**
     * Get the pathway of of the child by its id.
     * 
     * @param int $id
     */
    public function getPathwayByChildId($id)
    {
    	
        $select = $this->getSql()->select();
        $select->from(['child' => $this->getTable()])
        	->columns([])
            ->join(
                ['parent' => $this->getTable()],
                'child.' . self::COLUMN_LEFT . ' BETWEEN parent.' . self::COLUMN_LEFT . ' AND parent.' . self::COLUMN_RIGHT, 
                [Select::SQL_STAR],
                Select::JOIN_INNER
            )
            ->where(['child.' . $this->getPrimaryKey() . ' = ?' => $id])
            ->order('parent.' . self::COLUMN_LEFT);
        
        return $select;
    }
    
    /**
     *  
        SELECT `child`.*, (COUNT(`parent`.`productCategoryId`) - (`subTree`.`depth` + 1)) AS `depth` 
		FROM `productCategory` AS `child` 
		INNER JOIN `productCategory` AS `parent` ON `child`.`lft` BETWEEN `parent`.`lft` AND `parent`.`rgt` 
		INNER JOIN `productCategory` AS `subParent` ON `child`.`lft` BETWEEN `subParent`.`lft` AND `subParent`.`rgt` 
		INNER JOIN (
			SELECT `child`.`productCategoryId`, (COUNT(`parent`.`productCategoryId`) - 1) AS `depth` 
		        FROM `productCategory` AS `child` 
		        INNER JOIN `productCategory` AS `parent` ON `child`.`lft` BETWEEN `parent`.`lft` AND `parent`.`rgt` 
		        WHERE `child`.`productCategoryId` = '2' 
		        GROUP BY `child`.`productCategoryId` 
		        ORDER BY `child`.`lft` ASC
		) AS `subTree` ON `subParent`.`productCategoryId` = `subTree`.`productCategoryId` 
		GROUP BY `child`.`productCategoryId` 
		ORDER BY `child`.`lft` ASC
     * 
     * @param int $parentId
     * @param string $immediate
     */
    public function getDecendentsByParentId($parentId, $immediate=true)
    {
        $subTree = $this->getSql()->select()
            ->from(['child' => $this->getTable()])
            ->columns([
            	$this->primary,
            	'depth' => new Expression('(COUNT(parent.' . $this->getPrimaryKey() . ') - 1)')
            ])
            ->join(
                ['parent' => $this->getTable()],
                'child.' . self::COLUMN_LEFT . ' BETWEEN parent.' . self::COLUMN_LEFT . ' AND parent.' .self::COLUMN_RIGHT,
                [],
                Select::JOIN_INNER
            )
            ->where(['child.' . $this->getPrimaryKey() . ' = ?' => $parentId])
            ->group('child.' . $this->getPrimaryKey())
            ->order('child.' . self::COLUMN_LEFT);
    
        $select = $this->getSql()->select()
            ->from(['child' => $this->getTable()])
            ->columns([
            	Select::SQL_STAR,
            	'depth' => new Expression('(COUNT(parent.' . $this->getPrimaryKey() . ') - (subTree.depth + 1))')
            ])
            ->join(
                ['parent' => $this->getTable()],
                'child.' . self::COLUMN_LEFT . ' BETWEEN parent.' . self::COLUMN_LEFT . ' AND parent.' . self::COLUMN_RIGHT,
                [],
                Select::JOIN_INNER
            )
            ->join(
                ['subParent' => $this->getTable()],
                'child.' . self::COLUMN_LEFT . ' BETWEEN subParent.' . self::COLUMN_LEFT . ' AND subParent.' . self::COLUMN_RIGHT,
                [],
                Select::JOIN_INNER
            )
            ->join(
                ['subTree' => $subTree],
                'subParent.' . $this->getPrimaryKey() . ' = subTree.' . $this->getPrimaryKey(),
                [],
                Select::JOIN_INNER
            )
            ->group('child.' . $this->getPrimaryKey())
            ->order('child.' . self::COLUMN_LEFT);
    
        if (true === $immediate) {
            $select->having('depth = 1');
        }
    
        return $select;
    }
    
    /**
     * Updates left and right values of tree
     *
     * @param int $left_rgt
     * @param string $operator
     * @param int $offset
     */
    protected function updateTree($lft_rgt, $operator, $offset)
    {
    	$lft = new Where;
    	$rgt = new Where;
    	
    	$lftUpdate = $this->update([
    		self::COLUMN_LEFT => new Expression(self::COLUMN_LEFT . $operator . $offset)
    	], $lft->greaterThan(self::COLUMN_LEFT, $lft_rgt));
    	
    	$rgtUpdate = $this->update([
    		self::COLUMN_RIGHT => new Expression(self::COLUMN_RIGHT . $operator . $offset)
    	], $rgt->greaterThan(self::COLUMN_RIGHT, $lft_rgt));
    	
    	return [$lftUpdate, $rgtUpdate];
    }
    
    /**
     * Get the position of a child in the tree
     * 
     * @param int $id
     * @return array
     */
    protected function getPosition($id)
    {
        $cols = [
        	self::COLUMN_LEFT,
        	self::COLUMN_RIGHT,
        	'width' => new Expression(self::COLUMN_RIGHT . ' - ' . self::COLUMN_LEFT  . ' + 1'),
        ];
        
        $select = $this->getSelect();
        
        $where = new Where;
        $where->equalTo($this->getPrimaryKey(), $id);
        $select->columns($cols)->where($where);
        
        $row = $this->fetchResult($select, new ResultSet())->current();
        
        return $row;
    }
    
    /**
     * Insert a row into tree
     * 
     * @param array $data
     * @param number $position
     * @param string $insertType
     * @return int
     */
    public function insertRow(array $data, $position = 0, $insertType = self::INSERT_NODE)
    {
        $num = $this->fetchAll()->count();
        
        if ($num && $position) {
        	$row = $this->getPosition($position);
        	$lft_rgt = ($insertType === self::INSERT_NODE) ? $row->{self::COLUMN_RIGHT} : $row->{self::COLUMN_LEFT};
        } else {
        	$lft_rgt = 0;
        }
        
        $this->updateTree($lft_rgt, '+', 2);
        
        $data[self::COLUMN_LEFT] = $lft_rgt + 1;
        $data[self::COLUMN_RIGHT] = $lft_rgt + 2;
        
        $insertId = parent::insert($data);
        
        return $insertId;
    }
    
    /**
     * Deletes a row from tree.
     * 
	 * @param int|array $where
	 * @param string $table
	 * @return int number of affected rows
     */
    public function delete($where, $table = null)
    {
    	if (is_array($where)) {
    		$pk = $where[$this->getPrimaryKey()];
    	} else {
    		$pk = (int) $where;
    	}
    	
        $row = $this->getPosition($pk);
        
        $where = new Where;
        $where->between(self::COLUMN_LEFT, $row->{self::COLUMN_LEFT}, $row->{self::COLUMN_RIGHT});
        
        $result = parent::delete($where, $table);
        
        if ($result) {
            $this->updateTree($row->{self::COLUMN_RIGHT}, '-', $row->width);
        }
        
        return $result;
    }
}
