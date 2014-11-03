<?php
namespace UthandoCommon\Controller;

use Exception;
use Zend\Form\Form;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class AbstractCrudController
 *
 * @method Request getRequest()
 */
abstract class AbstractCrudController extends AbstractActionController
{   
	const ADD_ERROR			= 'record could not be saved to table %s due to a database error.';
	const ADD_SUCCESS		= 'row %s has been saved to database table %s.';
	const DELETE_ERROR		= 'row %s could not be deleted form table %s due to a database error.';
	const DELETE_SUCCESS	= 'row %s has been deleted from the database table %s.';
    const SAVE_ERROR		= 'row %s could not be saved to table %s due to a database error.';
    const SAVE_SUCCESS		= self::ADD_SUCCESS;
    
    const FORM_ERROR		= 'There were one or more issues with your submission. Please correct them as indicated below.';

    /**
     * @var array
     */
    protected $searchDefaultParams = [];

    /**
     * @var string
     */
    protected $serviceName;

    /**
     * @var array
     */
    protected $service = [];

    /**
     * @var string
     */
    protected $route;

    /**
     * @var bool
     */
    protected $addRouteParams = true;

    /**
     * @var array
     */
    protected $formOptions = [];

    /**
     * @var bool
     */
    protected $paginate = true;
    
    use SetExceptionMessages;

    /**
     * @return \Zend\Db\ResultSet\HydratingResultSet|\Zend\Db\ResultSet\ResultSet|\Zend\Paginator\Paginator
     */
    public function getPaginatorResults()
    {
        $params = $this->params()->fromPost();
        $limit = $this->params()->fromPost('count', 25);
        $page = (!isset($params['page'])) ? $this->params('page', 1) : $this->params()->fromPost('page', 1);

        $service = $this->getService();

        if ($this->paginate) {
            $service->usePaginator([
                'limit'	=> $limit,
                'page'	=> $page
            ]);
        }

        return $service->search(array_merge($this->searchDefaultParams, $params));
    }

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
    	$viewModel = new ViewModel([
    		'models' => $this->getPaginatorResults(),
    	]);

        if ($this->getRequest()->isXmlHttpRequest()) {
            $viewModel->setTerminal(true);
        }

        return $viewModel;
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function listAction()
    {
    	if (!$this->getRequest()->isXmlHttpRequest()) {
    		return $this->redirect()->toRoute($this->getRoute(), $this->params()->fromRoute());
    	}
    		
    	$viewModel = new ViewModel([
    		'models' => $this->getPaginatorResults(),
    	]);
    		
    	$viewModel->setTerminal(true);
    		
    	return $viewModel;
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
    	$request = $this->getRequest();

        $viewModel = new ViewModel();

        if ($request->isXmlHttpRequest()) {
            $viewModel->setTerminal(true);
        }
    
    	if ($request->isPost()) {
    		try {
    			$params = $this->params()->fromPost();
	    		$result = $this->getService()->add($params);

	    		if ($result instanceof Form) {
	    
	    			$this->flashMessenger()->addInfoMessage(self::FORM_ERROR);
	    
	    			return $viewModel->setVariables([
	    				'form' => $result,
	    			    'routeParams' => $this->params()->fromRoute(),
	    			]);
	    
	    		} else {
                    $tableName = $this->getService()->getMapper()->getTable();

                    if ($request->isXmlHttpRequest()) {
                        return new JsonModel([
                            'status'    => ($result) ? 'success' : 'danger',
                            'messages'  => ($result) ?
                                sprintf(self::ADD_SUCCESS, $result, $tableName) :
                                sprintf(self::ADD_ERROR, $tableName),
                        ]);
                    }

                    if ($result) {
                        $this->flashMessenger()->addSuccessMessage(sprintf(self::ADD_SUCCESS, $result, $tableName));
                    } else {
                        $this->flashMessenger()->addErrorMessage(sprintf(self::ADD_ERROR, $tableName));
                    }

                    return $this->redirect()->toRoute($this->getRoute(), $this->params()->fromRoute());
	    		}
    		} catch (Exception $e) {
                if ($request->isXmlHttpRequest()) {
                    return new JsonModel([
                        'status'    => 'danger',
                        'messages'  => $e->getMessage(),
                    ]);
                }

	    		$this->setExceptionMessages($e);
	    		return $this->redirect()->toRoute($this->getRoute(), array_merge($this->params()->fromRoute(), [
	    			'action' => 'list'
	    		]));
	    	}
    	}

        $form = $this->getService()->getForm();
        $argv = $this->getEventManager()->prepareArgs(compact('form'));
        $this->getEventManager()->trigger('add.action', $this, $argv);
    
    	return $viewModel->setVariables([
    		'form' => $form,
    	    'routeParams' => $this->params()->fromRoute(),
    	]);
    }

    /**
     * @return \Zend\Http\Response|JsonModel|ViewModel
     */
    public function editAction()
    {
    	$id = (int) $this->params('id', 0);

    	if (!$id) {
    		return $this->redirect()->toRoute($this->getRoute(), array_merge($this->params()->fromRoute(),[
    			'action' => 'add'
    		]));
    	}

        $viewModel = new ViewModel();

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $viewModel->setTerminal(true);
        }
    
    	try {

            $pk = $this->getService()->getMapper()->getPrimaryKey();
            $tableName = $this->getService()->getMapper()->getTable();
            $modelMethod = 'get' . ucwords($pk);

            $model = $this->getService()->getById($id);

	    	if ($request->isPost()) {
	    		
	    		// primary key ids must match. If not throw exception.

	    		$post = $this->params()->fromPost();

	    		if ($post[$pk] != $model->$modelMethod()) {
	    			throw new Exception('Primary keys do not match.');
	    		}
	    
	    		$result = $this->getService()->edit($model, $post);
	    
	    		if ($result instanceof Form) {
	    
	    			$this->flashMessenger()->addInfoMessage(self::FORM_ERROR);
	    
	    			return $viewModel->setVariables([
	    				'form'	=> $result,
	    				'model'	=> $model,
	    			]);
	    		} else {

                    if ($request->isXmlHttpRequest()) {
                        return new JsonModel([
                            'status'    => ($result) ? 'success' : 'danger',
                            'messages'  => ($result) ?
                                sprintf(self::SAVE_SUCCESS, $id, $tableName) :
                                sprintf(self::SAVE_ERROR, $id, $tableName),
                        ]);
                    }

	    			if ($result) {
	    				$this->flashMessenger()->addSuccessMessage(sprintf(self::SAVE_SUCCESS, $id, $tableName));
	    			} else {
	    				$this->flashMessenger()->addErrorMessage(sprintf(self::SAVE_ERROR, $id, $tableName));
	    			}
	    			
	    			$params = ($this->addRouteParams) ? $this->params()->fromRoute() : [];
	                
	    			return $this->redirect()->toRoute($this->getRoute(), $params);
	    		}
	    	}

            if (!$model->$modelMethod()) {
                throw new Exception('No records match key: ' . $id);
            }
	    	
	    	$form = $this->getService()->getForm($model);
	    	
    	} catch (Exception $e) {
            if ($request->isXmlHttpRequest()) {
                return new JsonModel([
                    'status'    => 'danger',
                    'messages'  => $e->getMessage(),
                ]);
            }

    		$this->setExceptionMessages($e);
    		return $this->redirect()->toRoute($this->getRoute(), array_merge($this->params()->fromRoute(), [
    			'action' => 'list'
    		]));
    	}
    
    	return $viewModel->setVariables([
    		'form'	=> $form,
    		'model'	=> $model,
    	]);
    }

    /**
     * @return \Zend\Http\Response|JsonModel
     */
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

                    if ($request->isXmlHttpRequest()) {
                        return new JsonModel([
                            'status'    => ($result) ? 'success' : 'danger',
                            'messages'  => ($result) ?
                                sprintf(self::DELETE_SUCCESS, $id, $tableName) :
                                sprintf(self::DELETE_ERROR, $id, $tableName),
                        ]);
                    }
    
    				if ($result) {
    					$this->flashMessenger()->addSuccessMessage(sprintf(self::DELETE_SUCCESS, $id, $tableName));
    				} else {
    					$this->flashMessenger()->addErrorMessage(sprintf(self::DELETE_ERROR, $id, $tableName));
    				}
    			} catch (Exception $e) {
                    if ($request->isXmlHttpRequest()) {
                        return new JsonModel([
                            'status'    => 'danger',
                            'messages'  => $e->getMessage(),
                        ]);
                    }

    				$this->setExceptionMessages($e);
    			}
    		}
    	}
    
    	return $this->redirect()->toRoute($this->getRoute(), $this->params()->fromRoute());
    }

    /**
     * @return string
     */
    protected function getServiceName()
    {
    	return $this->serviceName;
    }

    /**
     * @param string $serviceName
     * @return $this
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
        return $this;
    }
    
    /**
     * @param string $service
     * @return \UthandoCommon\Service\AbstractMapperService|\UthandoCommon\Service\AbstractService
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

    /**
     * @return array
     */
    public function getSearchDefaultParams()
    {
    	return $this->searchDefaultParams;
    }

    /**
     * @param $searchDefaultParams
     * @return $this
     */
    public function setSearchDefaultParams($searchDefaultParams)
    {
    	$this->searchDefaultParams = $searchDefaultParams;
    	return $this;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
    	return $this->route;
    }

    /**
     * @param string $route
     * @return $this
     */
    public function setRoute($route)
    {
    	$this->route = $route;
    	return $this;
    }
}
