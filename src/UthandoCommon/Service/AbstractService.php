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
    EventManagerAwareInterface,
    ServiceInterface
{
    use ServiceLocatorAwareTrait,
        EventManagerAwareTrait;

    /**
     * @var array
     */
    protected $services = [];

    /**
     * @var string
     */
    protected $serviceAlias;

    /**
     * @param $service
     * @return AbstractService
     * @throws ServiceException
     */
    public function getService($service)
    {
        if (!array_key_exists($service, $this->services)) {
            $this->setService($service);
        }

        return $this->services[$service];
    }

    /**
     * @param $service
     * @return $this
     * @throws ServiceException
     */
    public function setService($service)
    {
        $sl = $this->getServiceLocator();

        if (!$sl->has($service)) {
            throw new ServiceException($service . ' is not found in the service manager');
        }

        $this->services[$service] = $sl->get($service);

        return $this;
    }

    /**
     * Gets the default form for the service.
     *
     * @param ModelInterface $model
     * @param array $data
     * @param array $options
     * @param bool $useInputFilter
     * @param bool $useHydrator
     * @return Form
     */
	public function getForm(ModelInterface $model=null, array $data=null, $useInputFilter=false, $useHydrator=false)
	{
		$sl = $this->getServiceLocator();
        /* @var $formElementManager \Zend\Form\FormElementManager */
		$formElementManager = $sl->get('FormElementManager');
		/* @var $form \Zend\Form\Form */
		$form = $formElementManager->get($this->serviceAlias);
		
		$argv = compact('form', 'model', 'data');
		
		$this->getEventManager()->trigger('form.init', $this, $this->prepareEventArguments($argv));
		
		if ($useInputFilter) {
			$form->setInputFilter($this->getInputFilter());
		}
		
		if ($useHydrator) {
			$form->setHydrator($this->getHydrator());
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
	 * Gets the default input filter from InputFilterManager
     *
	 * @return \Zend\InputFilter\InputFilter
	 */
	public function getInputFilter()
	{
	    $sl = $this->getServiceLocator();
        /* @var $inputFilterManager \Zend\InputFilter\InputFilterPluginManager */
        $inputFilterManager = $sl->get('InputFilterManager');
        $inputFilter = $inputFilterManager->get($this->serviceAlias);

	    return $inputFilter;
	}

    /**
     * @return \Zend\Stdlib\Hydrator\HydratorInterface
     */
    public function getHydrator()
    {
        $sl = $this->getServiceLocator();
        /* @var $hydratorManager \Zend\Stdlib\Hydrator\HydratorPluginManager */
        $hydratorManager = $sl->get('HydratorManager');
        $hydrator = $hydratorManager->get($this->serviceAlias);

        return $hydrator;
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
	public function getConfig($key)
	{
		$config = $this->getServiceLocator()->get('config');
		return $config[$key];
	}
}
