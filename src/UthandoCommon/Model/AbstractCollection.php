<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Model
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Model;

use ArrayAccess;
use Countable;
use Iterator;
use SeekableIterator;

/**
 * Class Collection
 *
 * @package UthandoCommon\Model
 */
abstract class AbstractCollection implements Iterator, Countable, ArrayAccess, SeekableIterator
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
     * @param array $entities
     */
    public function init(array $entities = [])
    {
        if (!empty($entities)) {
            $this->setEntities($entities);
        }

        $this->rewind();
    }

    /**
     * adds an entity to the collection
     *
     * @param object $entity
     * @return $this
     * @throws CollectionException
     */
    public function add($entity)
    {
        $class = $this->entityClass;

        if (!$entity instanceof $class) {
            throw new CollectionException('class must an instance of ' . $class);
        }

        $this->entities[] = $entity;

        return $this;
    }

    /**
     * filter out all entities that don't belong to the entity class
     *
     * @param $entities
     * @return array
     */
    public function checkEntities($entities)
    {
        return array_filter($entities, function ($val) {
            $entityClass = $this->entityClass;
            return ($val instanceof $entityClass);
        });
    }

    /**
     * Set the entities stored in the collection
     *
     * @param array $entities
     */
    public function setEntities(array $entities)
    {
        $this->entities = $this->checkEntities($entities);
    }

    /**
     * Get the entities stored in the collection
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @return object $entityClass
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Clear the collection
     *
     * @return void
     */
    public function clear()
    {
        $this->entities = [];
    }

    /**
     * Reset the collection
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->entities);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->entities);
    }

    /**
     * @return void
     */
    public function next()
    {
        next($this->entities);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->entities);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return ($this->current() !== false);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->entities);
    }

    /**
     * Add an entity to the collection
     *
     * @param mixed $key
     * @param mixed $entity
     * @return bool|void
     * @throws CollectionException
     */
    public function offsetSet($key, $entity)
    {
        $class = $this->entityClass;

        if ($entity instanceof $class) {
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
     *
     * @param mixed $key
     * @return bool|void
     */
    public function offsetUnset($key)
    {
        $entityClass = $this->entityClass;

        if ($key instanceof $entityClass) {
            $this->entities = array_filter($this->entities, function ($v) use ($key) {
                return $v !== $key;
            });
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
     *
     * @param mixed $key
     * @return mixed|null
     */
    public function offsetGet($key)
    {
        return isset($this->entities[$key]) ?
            $this->entities[$key] :
            null;
    }

    /**
     * Check if the specified entity exists in the collection
     *
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return isset($this->entities[$key]);
    }

    /**
     * Seek to the given index.
     *
     * @param int $index
     * @throws CollectionException
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
