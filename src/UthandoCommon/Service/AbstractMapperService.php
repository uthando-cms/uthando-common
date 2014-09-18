<?php

namespace UthandoCommon\Service;

use UthandoCommon\Model\ModelInterface;
use UthandoCommon\UthandoException;
use Zend\Form\Form;

class AbstractMapperService extends AbstractService implements MapperServiceInterface
{
    /**
     * @var array
     */
    protected $mappers = [];

    /**
     * @var string
     */
    protected $mapperClass;

    /**
     * return one or more records from database by id
     *
     * @param $id
     * @return array|mixed|ModelInterface
     */
    public function getById($id)
    {
        $id = (int) $id;

        $model = $this->getCacheItem($id);

        if (!$model) {
            $model = $this->getMapper()->getById($id);
            $this->setCacheItem($id, $model);
        }

        return $model;
    }

    /**
     * fetch all records form database
     *
     * @return \Zend\Db\ResultSet\ResultSet|\Zend\Paginator\Paginator|\Zend\Db\ResultSet\HydratingResultSet
     */
    public function fetchAll()
    {
        return $this->getMapper()->fetchAll();
    }

    /**
     * basic search on database
     *
     * @param array $post
     * @return \Zend\Db\ResultSet\ResultSet|\Zend\Paginator\Paginator|\Zend\Db\ResultSet\HydratingResultSet
     */
    public function search(array $post)
    {
        $sort = (isset($post['sort'])) ? (string) $post['sort'] : '';
        unset($post['sort'], $post['count'], $post['offset'], $post['page']);

        $searches = array();

        foreach($post as $key => $value) {
            $searches[] = [
                'searchString'	=> (string) $value,
                'columns'		=> explode('-', $key),
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
        $model = $this->getMapper()->getModel();
        $form  = ($form) ? $form : $this->getForm($model, $post, true, true);

        $argv = compact('post', 'form');
        $argv = $this->prepareEventArguments($argv);
        $this->getEventManager()->trigger('pre.add', $this, $argv);

        if (!$form->isValid()) {
            return $form;
        }

        $saved = $this->save($form->getData());

        if ($saved) {
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

        if ($data instanceof ModelInterface) {
            $data = $this->getMapper()->extract($data);
        }

        $pk = $this->getMapper()->getPrimaryKey();
        $id = $data[$pk];
        unset($data[$pk]);

        if (0 === $id || null === $id || '' === $id) {
            $result = $this->getMapper()->insert($data);
        } else {
            if ($this->getById($id)) {
                $result = $this->getMapper()->update($data, [$pk => $id]);
                $this->removeCacheItem($id);
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
        $result = $this->getMapper()->delete([
            $this->getMapper()->getPrimaryKey() => $id
        ]);

        $this->removeCacheItem($id);

        return $result;
    }

    /**
     * gets the mapper class for this service
     *
     * @param null|string $mapperClass
     * @return \UthandoCommon\Mapper\AbstractMapper
     * @throws UthandoException
     */
    public function getMapper($mapperClass = null)
    {
        $mapperClass = ($mapperClass) ?: $this->serviceAlias;

        if (!array_key_exists($mapperClass, $this->mappers)) {
            $this->setMapper($mapperClass);
        }

        return $this->mappers[$mapperClass];
    }

    /**
     * Sets mapper in mapper array for reuse.
     *
     * @param string $mapperClass
     * @throws UthandoException
     * @return $this
     */
    public function setMapper($mapperClass)
    {
        $sl = $this->getServiceLocator();
        /* @var $mapperManager \UthandoCommon\Mapper\MapperManager */
        $mapperManager = $sl->get('UthandoMapperManager');
        $mapper = $mapperManager->get($mapperClass);

        $this->mappers[$mapperClass] = $mapper;

        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function usePaginator($options = [])
    {
        $this->getMapper()->usePaginator($options);
        return $this;
    }
} 