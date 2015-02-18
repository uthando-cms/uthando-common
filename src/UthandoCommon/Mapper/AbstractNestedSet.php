<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */
namespace UthandoCommon\Mapper;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Expression;

/**
 * Class AbstractNestedSet
 * @package UthandoCommon\Mapper
 * @method NestedSetInterface getHydrator()
 */
abstract class AbstractNestedSet extends AbstractDbMapper
{
	const INSERT_NODE	= 'insert';
	const INSERT_CHILD	= 'insertSub';
	const COLUMN_LEFT	= 'lft';
	const COLUMN_RIGHT	= 'rgt';

    /**
     * Gets all items in tree.
     *
     * @return \Zend\Db\ResultSet\HydratingResultSet|ResultSet|\Zend\Paginator\Paginator
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
     * @return Select
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
     * @return \Zend\Db\Sql\Select
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
     * @param $parentId
     * @param bool $immediate
     * @return Select
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
        
        $depth = new Expression('(COUNT(parent.' . $this->getPrimaryKey() . ') - (subTree.depth + 1))');
    
        $select = $this->getSql()->select()
            ->from(['child' => $this->getTable()])
            ->columns([
            	Select::SQL_STAR,
            	'depth' => $depth,
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
            // Hack for sqlite as having does not work otherwise.
            if ('SQLite' === $this->getAdapter()->getPlatform()->getName()) {
                $select->having($depth->getExpression() . ' = 1');
            } else {
                $select->having('depth = 1');
            }
        }
    
        return $select;
    }

    /**
     * Updates left and right values of tree
     *
     * @param $lft_rgt
     * @param string $operator
     * @param int $offset
     * @return array
     * @internal param int $left_rgt
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
     * @return \UthandoCommon\Model\NestedSetInterface $row
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

        $row = $this->fetchResult($select)->current();
        
        return $row;
    }

    /**
     * Insert a row into tree
     *
     * @param array $data
     * @param int|number $position
     * @param string $insertType
     * @return int
     */
    public function insertRow(array $data, $position = 0, $insertType = self::INSERT_NODE)
    {
        $num = $this->fetchAll()->count();
        
        if ($num && $position) {
        	$row = $this->getPosition($position);
        	$lft_rgt = ($insertType === self::INSERT_NODE) ? $row->getRgt() : $row->getLft();
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
     * Move node and/or it's children
     *
     * @param array $data
     * @param int $position
     * @param string $insertType
     * @return int
     */
    public function move(array $data, $position = 0, $insertType = self::INSERT_NODE)
    {
        // get key of node we are moving.
        $id = $data[$this->getPrimaryKey()];

        $this->getHydrator()->addDepth();

        // node and it's children
        $select = $this->getDecendentsByParentId($id, false);
        $nodes = $this->fetchResult($select);

        // get the width of the node.
        $width = $nodes->current()->width();

        // take out the left and right values of list from database.
        $where = new Where();
        $where->between(self::COLUMN_LEFT, $nodes->current()->getLft(), $nodes->current()->getRgt());
        $result = $this->update([
            'lft' => null,
            'rgt' => null,
        ], $where);

        // update left and right values.
        $this->updateTree($nodes->current()->getRgt(), '-', $width);

        // get the new parent and it's children
        // and make room for moved node
        if ($position != 0) {
            /* @var $parent \UthandoCommon\Model\NestedSet */
            $parent = $this->getById($position);
            $lft_rgt = ($insertType === self::INSERT_NODE) ? $parent->getRgt() : $parent->getLft();
        } else {
            $lft_rgt = 0;
        }

        // update left and right values
        $this->updateTree($lft_rgt, '+', $width);

        // insert moved category.
        $reduce = $nodes->current()->getRgt() - $width;
        $getter = 'get' . ucfirst($this->getPrimaryKey());

        /* @var $row \UthandoCommon\Model\NestedSet */
        foreach ($nodes as $row) {
            $lft = ($row->getLft() - $reduce);
            $rgt = ($row->getRgt() - $reduce);

            if ($position != 0) {
                $rgt += $lft_rgt;
                $lft += $lft_rgt;
            }

            $result = $this->update([
                'rgt' => $rgt,
                'lft' => $lft,
            ], [$this->getPrimaryKey() => $row->$getter()]);
        }

        return $result;
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
        $where->between(self::COLUMN_LEFT, $row->getLft(), $row->getRgt());

        $result = parent::delete($where, $table);
        
        if ($result) {
            $this->updateTree($row->getRgt(), '-', $row->width());
        }
        
        return $result;
    }
}
