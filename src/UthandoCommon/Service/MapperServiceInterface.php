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

use UthandoCommon\Mapper\AbstractMapper;
use UthandoCommon\Mapper\MapperInterface;
use UthandoCommon\Model\ModelInterface;
use Zend\Form\Form;

/**
 * Interface MapperServiceInterface
 * @package UthandoCommon\Service
 */
interface MapperServiceInterface
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
     * Gets mapper class
     *
     * @param string $mapperClass
     * @param array $options
     * @return MapperInterface
     */
    public function getMapper($mapperClass = null, array $options = []);

    /**
     * Sets mapper class.
     *
     * @param string $mapperClass
     * @param array $options
     * @return $this
     */
    public function setMapper($mapperClass, array $options = []);

    /**
     * @param array $options
     * @return mixed
     */
    public function usePaginator($options = []);
} 