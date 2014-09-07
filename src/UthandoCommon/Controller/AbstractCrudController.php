<?php
namespace UthandoCommon\Controller;

use Exception;
use UthandoCommon\Controller\SetExceptionMessages;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

abstract class AbstractCrudController extends AbstractActionController
{   
	const ADD_ERROR			= 'record could not be saved to table %s due to a database error.';
	const ADD_SUCCESS		= 'row %s has been saved to database table %s.';
	const DELETE_ERROR		= 'row %s could not be deleted form table %s due to a database error.';
	const DELETE_SUCCESS	= 'row %s has been deleted from the database table %s.';
    const SAVE_ERROR		= 'row %s could not be saved to table %s due to a database error.';
    const SAVE_SUCCESS		= self::ADD_SUCCESS;
    
    const FORM_ERROR		= 'There were one or more isues with your submission. Please correct them as indicated below.';
    
    protected $searchDefaultParams = [];
    protected $serviceName;
    protected $service = [];
    protected $route;
    protected $addRouteParams = true;
    
    use SetExceptionMessages;
    
    public function getPaginatorResults($page, $params, $limit = 25)
    {
        return $this->getService()->usePaginator([
			'limit'	=> $limit,
			'page'	=> $page
		])->search(array_merge($this->searchDefaultParams, $params));
    }
    
    public function indexAction()
    {
    	$page = $this->params()->fromRoute('page', 1);
    		
    	return new ViewModel([
    		'models' => $this->getPaginatorResults($page, $this->getSearchDefaultParams()),
    	]);
    }
    
    public function listAction()
    {
    	if (!$this->getRequest()->isXmlHttpRequest()) {
    		return $this->redirect()->toRoute($this->getRoute(), $this->params()->fromRoute());
    	}
    		
    	$params = $this->params()->fromPost();
    		
    	$viewModel = new ViewModel([
    		'models' => $this->getPaginatorResults($params['page'], $params, $params['count']),
    	]);
    		
    	$viewModel->setTerminal(true);
    		
    	return $viewModel;
    }
    
    public function addAction()
    {
    	$request = $this->getRequest();
    
    	if ($request->isPost()) {
    		try {
    			$params = $this->params()->fromPost();
	    		$result = $this->getService()->add($params);
	    
	    		if ($result instanceof Form) {
	    
	    			$this->flashMessenger()->addInfoMessage(self::FORM_ERROR);
	    
	    			return new ViewModel([
	    				'form' => $result,
	    			    'routeParams' => $this->params()->fromRoute(),
	    			]);
	    
	    		} else {
                    $tableName = $this->getService()->getMapper()->getTable();

	    			if ($result) {
	    				$this->flashMessenger()->addSuccessMessage(sprintf(self::ADD_SUCCESS, $result, $tableName));
	    			} else {
	    				$this->flashMessenger()->addErrorMessage(sprintf(self::ADD_ERROR, $tableName));
	    			}
	    
	    			return $this->redirect()->toRoute($this->getRoute(), $this->params()->fromRoute());
	    		}
    		} catch (Exception $e) {
	    		$this->setExceptionMessages($e);
	    		return $this->redirect()->toRoute($this->getRoute(), array_merge($this->params()->fromRoute(), [
	    			'action' => 'list'
	    		]));
	    	}
    	}
    
    	return new ViewModel([
    		'form' => $this->getService()->getForm(),
    	    'routeParams' => $this->params()->fromRoute(),
    	]);
    }
    
    public function editAction()
    {
    	$id = (int) $this->params('id', 0);
    	if (!$id) {
    		return $this->redirect()->toRoute($this->getRoute(), array_merge($this->params()->fromRoute(),[
    			'action' => 'add'
    		]));
    	}
    
    	try {
    		$model = $this->getService()->getById($id);
    
	    	$request = $this->getRequest();
	    
	    	if ($request->isPost()) {
	    		
	    		// primary key ids must match. If not throw eception.
	    		$pk = $this->getService()->getMapper()->getPrimaryKey();
	    		$tableName = $this->getService()->getMapper()->getTable();
	    		$modelMethod = 'get' . ucwords($pk);
	    		$post = $this->params()->fromPost();
	    		
	    		if ($post[$pk] != $model->$modelMethod()) {
	    			throw new Exception('Primary keys do not match.');
	    		}
	    
	    		$result = $this->getService()->edit($model, $post);
	    
	    		if ($result instanceof Form) {
	    
	    			$this->flashMessenger()->addInfoMessage(self::FORM_ERROR);
	    
	    			return new ViewModel([
	    				'form'	=> $result,
	    				'model'	=> $model,
	    			]);
	    		} else {
	    			if ($result) {
	    				$this->flashMessenger()->addSuccessMessage(sprintf(self::SAVE_SUCCESS, $id, $tableName));
	    			} else {
	    				$this->flashMessenger()->addErrorMessage(sprintf(self::SAVE_ERROR, $id, $tableName));
	    			}
	    			
	    			$params = ($this->addRouteParams) ? $this->params()->fromRoute() : [];
	                
	    			return $this->redirect()->toRoute($this->getRoute(), $params);
	    		}
	    	}
	    	
	    	$form = $this->getService()->getForm($model);
	    	
    	} catch (Exception $e) {
    		$this->setExceptionMessages($e);
    		return $this->redirect()->toRoute($this->getRoute(), array_merge($this->params()->fromRoute(), [
    			'action' => 'list'
    		]));
    	}
    
    	return new ViewModel([
    		'form'	=> $form,
    		'model'	=> $model,
    	]);
    }
    
    public function deleteAction()
    {
    	$request = $this->getRequest();
    
    	$tableName = $this->getService()->getMapper()->getTable();
    	$pk = $this->getService()->getMapper()->getPrimaryKey();
    	$id = $request->getPost($pk);
    
    	if (!$id) {
    		return $this->redirect()->toRoute($this->getRoute(), $this->params()->fromRoute());
    	}
    
    	if ($request->isPost()) {
    		$del = $request->getPost('submit', 'No');
    
    		if ($del == 'delete') {
    			try {
    				$result = $this->getService()->delete($id);
    
    				if ($result) {
    					$this->flashMessenger()->addSuccessMessage(sprintf(self::DELETE_SUCCESS, $id, $tableName));
    				} else {
    					$this->flashMessenger()->addErrorMessage(sprintf(self::DELETE_ERROR, $id, $tableName));
    				}
    			} catch (Exception $e) {
    				$this->setExceptionMessages($e);
    			}
    		}
    	}
    
    	return $this->redirect()->toRoute($this->getRoute(), $this->params()->fromRoute());
    }
    
    protected function getServiceName()
    {
    	return $this->serviceName;
    }
    
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
        return $this;
    }
    
    /**
     * @param string $service
     * @return \UthandoCommon\Service\AbstractService
     */
    protected function getService($service = null)
    {
        $service = (is_string($service)) ? $service : $this->getServiceName();
        
    	if (!isset($this->service[$service])) {
    		$sl = $this->getServiceLocator();
    		$this->service[$service] = $sl->get($service);
    	}
    
    	return $this->service[$service];
    }
    
    public function getSearchDefaultParams()
    {
    	return $this->searchDefaultParams;
    }
    
    public function setSearchDefaultParams($searchDefaultParams)
    {
    	$this->searchDefaultParams = $searchDefaultParams;
    	return $this;
    }
    
    public function getRoute()
    {
    	return $this->route;
    }
    
    public function setRoute($route)
    {
    	$this->route = $route;
    	return $this;
    }
}
