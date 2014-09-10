<?php

namespace UthandoCommon\Service;

use UthandoCommon\Mapper\AbstractMapper;
use UthandoCommon\Model\ModelInterface;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Interface ServiceInterface
 * @package UthandoCommon\Service
 */
interface ServiceInterface
{
    /**
     * return one or more records from database by id
     *
     * @param $id
     * @return array|mixed|ModelInterface
     */
    public function getById($id);

    /**
     * fetch all records form database
     *
     * @return \Zend\Db\ResultSet\ResultSet|\Zend\Paginator\Paginator|\Zend\Db\ResultSet\HydratingResultSet
     */
    public function fetchAll();

    /**
     * basic search on database
     *
     * @param array $post
     * @return \Zend\Db\ResultSet\ResultSet|\Zend\Paginator\Paginator|\Zend\Db\ResultSet\HydratingResultSet
     */
    public function search(array $post);

    /**
     * prepare and return form
     *
     * @param array $post
     * @param \Zend\Form\Form $form
     * @return int|Form
     */
    public function add(array $post, Form $form = null);

    /**
     * prepare data to be updated and saved into database.
     *
     * @param ModelInterface $model
     * @param array $post
     * @param Form $form
     * @return int results from self::save()
     */
    public function edit(ModelInterface $model, array $post, Form $form = null);

    /**
     * updates a row if id is supplied else insert a new row
     *
     * @param array|ModelInterface $data
     * @throws ServiceException
     * @return int $results number of rows affected or insertId
     */
    public function save($data);

    /**
     * delete row from database
     *
     * @param int $id
     * @return int $result number of rows affected
     */
    public function delete($id);

    /**
     * Gets the mapper class
     *
     * @return AbstractMapper
     */
    public function getMapper();

    /**
     * Sets mapper class.
     *
     * @param string $mapper
     * @return $this
     */
    public function setMapper($mapper);

    /**
     * Gets the default form for the service.
     *
     * @param ModelInterface $model
     * @param array $data
     * @param bool $useInputFilter
     * @param bool $useHydrator
     * @return Form $form
     */
    public function getForm(ModelInterface $model=null, array $data=null, $useInputFilter=false, $useHydrator=false);

    /**
     * Gets the default input filter
     *
     * @return InputFilter
     */
    public function getInputFilter();

    /**
     * @param $argv
     * @return \ArrayObject
     */
    public function prepareEventArguments($argv);

    /**
     * get application config option by its key.
     *
     * @param string $key
     * @return array $config
     */
    public function getConfig($key);

    /**
     * @param array $options
     * @return mixed
     */
    public function usePaginator($options = []);
} 