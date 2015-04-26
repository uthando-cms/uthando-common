<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Service
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */
namespace UthandoCommon\Service;

use UthandoCommon\Cache\CacheStorageAwareInterface;
use UthandoCommon\Cache\CacheTrait;
use UthandoCommon\Mapper\MapperInterface;
use UthandoCommon\Mapper\MapperManager;
use UthandoCommon\Model\ModelInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Form\Form;
use Zend\Paginator\Paginator;

/**
 * Class AbstractMapperService
 * @package UthandoCommon\Service
 */
class AbstractMapperService extends AbstractService implements MapperServiceInterface, CacheStorageAwareInterface
{
    use CacheTrait;

    /**
     * @var array
     */
    protected $mappers = [];

    /**
     * return one or more records from database by id
     *
     * @param $id
     * @param null $col
     * @return array|mixed|ModelInterface
     */
    public function getById($id, $col = null)
    {
        $id = (int) $id;
        $model = $this->getCacheItem($id);

        if (!$model) {
            $model = $this->getMapper()->getById($id, $col);
            
            if ($this->useCache) {
                $this->setCacheItem($id, $model);
            }
        }

        return $model;
    }

    /**
     * fetch all records form database
     *
     * @return ResultSet|Paginator|HydratingResultSet
     */
    public function fetchAll()
    {
        return $this->getMapper()->fetchAll();
    }

    /**
     * basic search on database
     *
     * @param array $post
     * @return ResultSet|Paginator|HydratingResultSet
     */
    public function search(array $post)
    {
        $sort = (isset($post['sort'])) ? $post['sort'] : '';
        unset($post['sort'], $post['count'], $post['offset'], $post['page']);

        $searches = [];

        foreach ($post as $key => $value) {
            $searches[] = [
                'searchString' => $value,
                'columns'      => explode('-', $key),
            ];
        }

        $models = $this->getMapper()->search($searches, $sort);

        return $models;
    }

    /**
     * prepare and return form
     *
     * @param array $post
     * @param Form $form
     * @return int|Form
     */
    public function add(array $post, Form $form = null)
    {
        $model = $this->getModel();
        $form  = ($form) ? $form : $this->getForm($model, $post, true, true);

        $argv = compact('post', 'form');
        $argv = $this->prepareEventArguments($argv);
        $this->getEventManager()->trigger('pre.add', $this, $argv);

        if (!$form->isValid()) {
            return $form;
        }

        $saved = $this->save($form->getData());

        if ($saved) {
            $argv = compact('post', 'form', 'saved');
            $argv = $this->prepareEventArguments($argv);
            $this->getEventManager()->trigger('post.add', $this, $argv);
        }

        return $saved;
    }

    /**
     * prepare data to be updated and saved into database.
     *
     * @param ModelInterface $model
     * @param array $post
     * @param Form $form
     * @return int results from self::save()
     */
    public function edit(ModelInterface $model, array $post, Form $form = null)
    {
        $form  = ($form) ? $form : $this->getForm($model, $post, true, true);

        $argv = compact('model', 'post', 'form');
        $argv = $this->prepareEventArguments($argv);
        $this->getEventManager()->trigger('pre.edit', $this, $argv);

        if (!$form->isValid()) {
            return $form;
        }

        $saved = $this->save($form->getData());

        if ($saved) {
            $this->getEventManager()->trigger('post.edit', $this, $argv);
        }

        return $saved;
    }

    /**
     * updates a row if id is supplied else insert a new row
     *
     * @param array|ModelInterface $data
     * @throws ServiceException
     * @return int $results number of rows affected or insertId
     */
    public function save($data)
    {
        $argv = compact('data');
        $argv = $this->prepareEventArguments($argv);
        $this->getEventManager()->trigger('pre.save', $this, $argv);
        $data = $argv['data'];

        if ($data instanceof ModelInterface) {
            $data = $this->getHydrator()->extract($data);
        }

        $pk = $this->getMapper()->getPrimaryKey();
        $id = $data[$pk];
        unset($data[$pk]);

        // if values not set then don't save them.
        // doesn't work so allow null values.
        foreach ($data as $key => $value) {
            if ('' == $value) {
                unset($data[$key]);
            }
        }

        if (0 === $id || null === $id || '' === $id) {
            $result = $this->getMapper()->insert($data);
        } else {
            if ($this->getById($id)) {
                $this->removeCacheItem($id);
                $result = $this->getMapper()->update($data, [$pk => $id]);
            } else {
                throw new ServiceException('ID ' . $id . ' does not exist');
            }
        }

        return $result;
    }

    /**
     * delete row from database
     *
     * @param int $id
     * @return int $result number of rows affected
     */
    public function delete($id)
    {
        $model = $this->getById($id);

        $argv = compact('id', 'model');
        $argv = $this->prepareEventArguments($argv);

        $this->getEventManager()->trigger('pre.delete', $this, $argv);

        $result = $this->getMapper()->delete([
            $this->getMapper()->getPrimaryKey() => $id
        ]);

        if ($result) {
            $this->removeCacheItem($id);
            $this->getEventManager()->trigger('post.delete', $this, $argv);
        }

        return $result;
    }

    /**
     * gets the mapper class for this service
     *
     * @param null|string $mapperClass
     * @param array $options
     * @return MapperInterface
     */
    public function getMapper($mapperClass = null, array $options = [])
    {
        $mapperClass = ($mapperClass) ?: $this->serviceAlias;

        if (!array_key_exists($mapperClass, $this->mappers)) {
            $this->setMapper($mapperClass, $options);
        }

        return $this->mappers[$mapperClass];
    }

    /**
     * Sets mapper in mapper array for reuse.
     *
     * @param string $mapperClass
     * @param array $options
     * @return $this
     */
    public function setMapper($mapperClass, array $options = [])
    {
        $sl = $this->getServiceLocator();
        /* @var $mapperManager MapperManager */
        $mapperManager = $sl->get('UthandoMapperManager');

        $defaultOptions = [
            'model'     => $this->serviceAlias,
            'hydrator'  => $this->serviceAlias,
        ];

        $options = array_merge($defaultOptions, $options);

        $mapper = $mapperManager->get($mapperClass, $options);

        $this->mappers[$mapperClass] = $mapper;

        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function usePaginator($options = [])
    {
        $this->getMapper()
            ->setUsePaginator(true)
            ->setPaginatorOptions($options);

        return $this;
    }
} 