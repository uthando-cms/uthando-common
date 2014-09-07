<?php
namespace UthandoCommon\Service;

use UthandoCommon\Model\ModelInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

abstract class AbstractService implements 
ServiceLocatorAwareInterface,
EventManagerAwareInterface
{
    use ServiceLocatorAwareTrait;
    
    use EventManagerAwareTrait;
	
	/**
	 * @var \UthandoCommon\Mapper\AbstractMapper
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
     * @param $id
     * @return array|\ArrayObject|mixed|null|object
     */
    public function getById($id)
	{
		$id = (int) $id;
        $model = $this->getMapper()->getById($id);
		
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
                $this->getMapper()->removeCacheItem($id);
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

        $this->getMapper()->removeCacheItem($id);
		
		return $result;
	}

    /**
     * @return \UthandoCommon\Mapper\AbstractMapper
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
		$formManager = $sl->get('FormElementManager');
		/* @var $form \Zend\Form\Form */
		$form = $formManager->get($this->form);
		$form->init();
		
		$argv = compact('form', 'model', 'data');
		
		$this->getEventManager()->trigger('form.init', $this, $this->prepareEventArguments($argv));
		
		if ($useInputFilter) {
			$form->setInputFilter($this->getInputFilter());
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
	 * Gets the default input filter
     *
	 * @return \Zend\InputFilter\InputFilter
	 */
	public function getInputFilter()
	{
	    $sl = $this->getServiceLocator();
	    $inputFilter = $sl->get($this->inputFilter);
	    $inputFilter->init();
	    return $inputFilter;
	}

    /**
     * @param $argv
     * @return \ArrayObject
     */
    public function prepareEventArguments($argv)
	{
        /* @var $em \Zend\EventManager\EventManager */
        $em = $this->getEventManager();
	    $argv = $em->prepareArgs($argv);
	    return $argv;
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
