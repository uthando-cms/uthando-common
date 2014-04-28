<?php
namespace UthandoCommon\Service;

use UthandoCommon\Model\ModelInterface;
use UthandoCommon\Service\ServiceException;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class AbstractService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
	
	/**
	 * @var \Application\Mapper\AbstractMapper
	 */
	protected $mapper;
	
	/**
	 * @var string
	 */
	protected $form;
	
	/**
	 * @var string
	 */
	protected $inputFilter;
	
	/**
	 * @var string
	 */
	protected $mapperClass;
	
	/**
	 * return just one record from database
	 * 
	 * @param int $id
	 * @return AbstractModel|null
	 */
	public function getById($id)
	{
		$id = (int) $id;
		return $this->getMapper()->getById($id);
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
			$searches[] = array(
				'searchString'	=> (string) $value,
				'columns'		=> explode('-', $key),
			);
		}
		 
		$models = $this->getMapper()->search($searches, $sort);
		 
		return $models;
	}
	
	/**
	 * override this to populate relational records.
	 * 
	 * @param AbstractModel $model
	 * @param string $children
	 * @return AbstractModel $model
	 */
	public function populate($model, $children = false)
	{
		return $model;
	}
	
	/**
	 * prepare data to be inserted into database
	 * 
	 * @param array $post
	 * @return int results from self::save()
	 */
	public function add(array $post)
	{
		$model = $this->getMapper()->getModel();
		$form  = $this->getForm($model, $post, true, true);
	
		if (!$form->isValid()) {
			return $form;
		}
	
		return $this->save($form->getData());
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
		
		if (!$form->isValid()) {
			return $form;
		}
		
		return $this->save($form->getData());
	}
	
	/**
	 * updates a row if id is supplied else insert a new row
	 * 
	 * @param array|ModelInterface $data
	 * @throws ServiceException
	 * @return int $reults number of rows affected or insertId
	 */
	public function save($data)
	{
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
				$result = $this->getMapper()->update($data, array($pk => $id));
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
		$result = $this->getMapper()->delete(array(
			$this->getMapper()->getPrimaryKey() => $id
		));
		
		return $result;
	}
	
	/**
	 * @return \Application\Mapper\AbstractMapper
	 */
	public function getMapper()
	{
		if (!$this->mapper) {
			$sl = $this->getServiceLocator();
			$this->mapper = $sl->get($this->mapperClass);
		}
		
		return $this->mapper;
	}
	
	/**
	 * Gets the default form for the service.
	 * 
	 * @param ModelInterface $model
	 * @param array $data
	 * @param bool $useInputFilter
	 * @param bool $useHydrator
	 * @return Form $form
	 */
	public function getForm(ModelInterface $model=null, array $data=null, $useInputFilter=false, $useHydrator=false)
	{
		$sl = $this->getServiceLocator();
		/* @var $form \Zend\Form\Form */
		$form = $sl->get($this->form);
		$form->init();
		
		if ($useInputFilter) {
			$form->setInputFilter($sl->get($this->inputFilter));
			$form->getInputFilter()->init();
		}
		
		if ($useHydrator) {
			$form->setHydrator($this->getMapper()->getHydrator());
		}
		 
		if ($model) {
			$form->bind($model);
		}
		 
		if ($data) {
			$form->setData($data);
		}
	
		return $form;
	}
	
	/**
	 * get application config option by its key.
	 *
	 * @param string $key
	 * @return array $config
	 */
	protected function getConfig($key)
	{
		$config = $this->getServiceLocator()->get('config');
		return $config[$key];
	}
	
	public function usePaginator($options = [])
	{
		$this->getMapper()->usePaginator($options);
		return $this;
	}
}
