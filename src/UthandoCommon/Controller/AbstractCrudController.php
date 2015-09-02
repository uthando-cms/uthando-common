<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Controller
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Controller;

use Exception;
use Zend\Session\Container;
use Zend\EventManager\EventManager;
use Zend\Form\Form;
use Zend\Http\PHPEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class AbstractCrudController
 *
 * @package UthandoCommon\Controller
 * @method Request getRequest()
 * @method EventManager getEventManager()
 * @method Container sessionContainer($sessionName = null)
 * @method FlashMessenger flashMessenger()
 */
abstract class AbstractCrudController extends AbstractActionController
{   
	const ADD_ERROR			= 'record could not be saved to table %s due to a database error.';
	const ADD_SUCCESS		= 'row %s has been saved to database table %s.';
	const DELETE_ERROR		= 'row %s could not be deleted form table %s due to a database error.';
	const DELETE_SUCCESS	= 'row %s has been deleted from the database table %s.';
    const SAVE_ERROR		= 'no changes where applied to row %s.';
    const SAVE_SUCCESS		= self::ADD_SUCCESS;
    
    const FORM_ERROR		= 'There were one or more issues with your submission. Please correct them as indicated below.';

    use ServiceTrait;

    /**
     * @var array
     */
    protected $searchDefaultParams = [
        'count' => 25,
        'page' => 1,
    ];
    
    /**
     * @var array
     */
    protected $controllerSearchOverrides = [];

    /**
     * @var string
     */
    protected $route;

    /**
     * @var array
     */
    protected $routes = [];

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
    
    public function __construct()
    {
        $this->searchDefaultParams = array_merge($this->searchDefaultParams, $this->controllerSearchOverrides);
    }

    /**
     * @param bool $getParamsFromSession
     * @return \Zend\Db\ResultSet\HydratingResultSet|\Zend\Db\ResultSet\ResultSet|\Zend\Paginator\Paginator
     */
    public function getPaginatorResults($getParamsFromSession = true)
    {
        $params = array_merge($this->searchDefaultParams, $this->params()->fromPost());
        $session = $this->sessionContainer($this->getServiceName());

        if ($getParamsFromSession && !$this->params()->fromPost()) {
            $sessionParams = ($session->offsetGet('params')) ?: [];
            $params = array_merge($params, $sessionParams);
        }

        $session->offsetSet('params', $params);
        
        $service = $this->getService();

        if ($this->paginate) {
            $service->usePaginator([
                'limit'	=> $params['count'],
                'page'	=> $params['page'],
            ]);
        }
        
        return $service->search($params);
    }

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $models = $this->getPaginatorResults();

    	$viewModel = new ViewModel([
    		'models' => $models,
            'params' => $this->sessionContainer()->offsetGet('params'),
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
    		return $this->redirect()->toRoute($this->getRoute('list'), $this->params()->fromRoute());
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
                            'table'     => $tableName,
                            'rowId'     => $result,
                            'messages'  => ($result) ?
                                sprintf(self::ADD_SUCCESS, $result, $tableName) :
                                sprintf(self::ADD_ERROR, $tableName),
                        ]);
                    }

                    if ($result) {
                        $this->flashMessenger()->addSuccessMessage(sprintf(self::ADD_SUCCESS, $result, $tableName));
                    } else {
                        $this->flashMessenger()->addErrorMessage(sprintf(self::ADD_ERROR, $result, $tableName));
                    }

                    $routeParams = $this->params()->fromRoute();
                    $route = $this->getRoute('add');

                    if ('1' == $this->params()->fromPost('redirectToEdit', null)) {
                        $routeParams = array_merge($routeParams, [
                            'action'    => 'edit',
                            'id'        => $result,
                        ]);
                        $route = $route . '/edit';
                    }

                    return $this->redirect()->toRoute($route, $routeParams);
	    		}
    		} catch (Exception $e) {
                if ($request->isXmlHttpRequest()) {
                    return new JsonModel([
                        'status'    => 'danger',
                        'messages'  => $e->getMessage(),
                    ]);
                }

	    		$this->setExceptionMessages($e);
	    		return $viewModel->setVariables([
                    'form' => $this->getService()->getForm(null, $this->params()->fromPost()),
                    'routeParams' => $this->params()->fromRoute(),
                ]);
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

        $post = $this->params()->fromPost();
    
    	try {

            $pk = $this->getService()->getMapper()->getPrimaryKey();
            $tableName = $this->getService()->getMapper()->getTable();
            $modelMethod = 'get' . ucwords($pk);

            $model = $this->getService()->getById($id);

	    	if ($request->isPost()) {
	    		
	    		// primary key ids must match. If not throw exception.

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
	                
	    			return $this->redirect()->toRoute($this->getRoute('edit'), $params);
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

    		return $viewModel->setVariables([
			    'form' => $this->getService()->getForm(null, $post),
			    'routeParams' => $this->params()->fromRoute(),
			]);
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
    
    	return $this->redirect()->toRoute($this->getRoute('delete'), $this->params()->fromRoute());
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
     * @param null $route
     * @return string
     */
    public function getRoute($route = null)
    {
        if ($route && isset($this->routes[$route])) {
            $route = $this->routes['route'];
        } else {
            $route = $this->route;
        }

    	return $route;
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
