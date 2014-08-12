<?php
namespace UthandoCommon\Model;

use UthandoCommon\Model\CollectionException;
use Iterator;
use Countable;
use ArrayAccess;

abstract class Collection implements Iterator, Countable, ArrayAccess
{
    /**
     * collection of entities.
     * 
     * @var array
     */
    protected $entities = [];
    
    /**
     * entity class name
     * 
     * @var string
     */
    protected $entityClass;
    
    /**
     * Constructor
     */
    public function init(array $entities = [])
    {
        if (!empty($entities)) {
            $this->setEntities($entities);
        }
        
        $this->rewind();
    }
    
    /**
     * adds an entity to the colection
     * 
     * @param class $entity
     * @return \Application\Model\AbstractCollection
     */
    public function add($entity)
    {   
        $this->entities[] = $entity;
        return $this;
    }
    
    /**
     * Set the entities stored in the collection
     * 
     * @param array $entities
     */
    public function setEntities(array $entities)
    {
        $this->entities = $entities;
    }
    
    /**
     * Get the entities stored in the collection
     */
    public function getEntities()
    {
        return $this->entities;
    }
     
    /**
     * @return the $entityClass
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

	/**
     * @param string $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

	/**
     * Clear the collection
     */
    public function clear()
    {
        $this->entities = [];
    }
    
    /**
     * Reset the collection (implementation required by Iterator Interface)
     */
    public function rewind()
    {
        reset($this->entities);
    }
     
    /**
     * Get the current entity in the collection 
     * (implementation required by Iterator Interface)
     */
    public function current()
    {
        return current($this->entities);
    }
     
    /**
     * Move to the next entity in the collection 
     * (implementation required by Iterator Interface)
     */
    public function next()
    {
        next($this->entities);
    }
     
    /**
     * Get the key of the current entity in the collection 
     * (implementation required by Iterator Interface)
     */
    public function key()
    {
        return key($this->entities);
    }
     
    /**
     * Check if there're more entities in the collection 
     * (implementation required by Iterator Interface)
     */
    public function valid()
    {
        return ($this->current() !== false);
    }
     
    /**
     * Count the number of entities in the collection 
     * (implementation required by Countable Interface)
     */
    public function count()
    {
        return count($this->entities);
    }
     
    /**
     * Add an entity to the collection 
     * (implementation required by ArrayAccess interface)
     */
    public function offsetSet($key, $entity)
    {
        if ($entity instanceof $this->entityClass) {
            if (!isset($key)) {
                $this->entities[] = $entity;
            } else {
                $this->entities[$key] = $entity;
            }
            return true;
        }
        throw new CollectionException(
            'The specified entity is not allowed for this collection.'
        );
    }
     
    /**
     * Remove an entity from the collection 
     * (implementation required by ArrayAccess interface)
     */
    public function offsetUnset($key)
    {
        if ($key instanceof $this->entityClass) {
            $this->entities = array_filter(
                $this->entities,
                function ($v) use ($key) {
                    return $v !== $key;
                }
            );
            return true;
        }
        
        if (isset($this->entities[$key])) {
            unset($this->entities[$key]);
            return true;
        }
        
        return false;
    }
     
    /**
     * Get the specified entity in the collection 
     * (implementation required by ArrayAccess interface)
     */
    public function offsetGet($key)
    {
        return isset($this->entities[$key]) ?
            $this->entities[$key] :
            null;
    }
     
    /**
     * Check if the specified entity exists in the collection 
     * (implementation required by ArrayAccess interface)
     */
    public function offsetExists($key)
    {
        return isset($this->entities[$key]);
    }
    
    /**
     * Seek to the given index.
     *
     * @param int $index seek index
     */
    public function seek($index)
    {
        $this->rewind();
        $position = 0;
    
        while ($position < $index && $this->valid()) {
            $this->next();
            $position++;
        }
    
        if (!$this->valid()) {
            throw new CollectionException('Invalid seek position');
        }
    }
}
