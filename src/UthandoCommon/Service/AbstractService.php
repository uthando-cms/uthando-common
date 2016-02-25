<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Service
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Service;

use UthandoCommon\Model\ModelInterface;
use UthandoCommon\Model\ModelManager;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Form\Form;
use Zend\Form\FormElementManager;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\HydratorPluginManager;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Class AbstractService
 *
 * @package UthandoCommon\Service
 */
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
     * @var array
     */
    protected $formAliases = [];

    /**
     * @var array
     */
    protected $formOptions = [];

    /**
     * events to set up. This should be overridden in parent class.
     */
    public function attachEvents()
    {
    }

    /**
     * @param $service
     * @return AbstractService
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
     */
    public function setService($service)
    {
        $sl = $this->getServiceLocator();
        $this->services[$service] = $sl->get($service);

        return $this;
    }

    /**
     * Prepares form for the service.
     *
     * @param ModelInterface $model
     * @param array $data
     * @param bool|string $useInputFilter
     * @param bool|string $useHydrator
     * @return Form
     */
    public function prepareForm(ModelInterface $model = null, array $data = null, $useInputFilter = false, $useHydrator = false)
    {
        $argv = compact('model', 'data');
        $argv = $this->prepareEventArguments($argv);
        $this->getEventManager()->trigger('pre.form', $this, $argv);
        $data = $argv['data'];

        $sl = $this->getServiceLocator();
        /* @var $formElementManager FormElementManager */
        $formElementManager = $sl->get('FormElementManager');
        /* @var $form Form */

        $form = $formElementManager->get($this->serviceAlias, $this->formOptions);

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

        $argv = compact('form', 'model', 'data');

        $this->getEventManager()->trigger('form.init', $this, $this->prepareEventArguments($argv));

        return $form;
    }

    /**
     * Gets the default form or on specified for the service.
     *
     * @param null|string $name
     * @param array $options
     * @return Form
     */
    public function getForm($name = null, $options = [])
    {
        $name = ($name) ?: $this->serviceAlias;
        $sl = $this->getServiceLocator();

        /* @var $formElementManager FormElementManager */
        $formElementManager = $sl->get('FormElementManager');

        $argv = compact('options');

        $this->getEventManager()->trigger('pre.form.init', $this, $this->prepareEventArguments($argv));

        /* @var $form Form */
        $form = $formElementManager->get($name, $options);

        $argv = compact('form', 'options');

        $this->getEventManager()->trigger('post.form.init', $this, $this->prepareEventArguments($argv));

        return $form;
    }

    /**
     * @return array
     */
    public function getFormOptions()
    {
        return $this->formOptions;
    }

    /**
     * @param array $formOptions
     */
    public function setFormOptions($formOptions)
    {
        $this->formOptions = $formOptions;
    }

    /**
     * Gets model from ModelManager
     *
     * @param null|string $model
     * @return ModelInterface
     */
    public function getModel($model = null)
    {
        $model          = ($model) ?: $this->serviceAlias;
        $sl             = $this->getServiceLocator();

        /* @var $modelManager ModelManager */
        $modelManager   = $sl->get('UthandoModelManager');
        $model          = $modelManager->get($model);

        return $model;
    }

    /**
     * Gets input filter from InputFilterManager
     *
     * @param null|string $inputFilter
     * @return InputFilter
     */
    public function getInputFilter($inputFilter = null)
    {
        $inputFilter        = ($inputFilter) ?: $this->serviceAlias;
        $sl                 = $this->getServiceLocator();

        /* @var $inputFilterManager InputFilterPluginManager */
        $inputFilterManager = $sl->get('InputFilterManager');
        $inputFilter        = $inputFilterManager->get($inputFilter);

        return $inputFilter;
    }

    /**
     * Gets hydrator from HydratorManager
     *
     * @param string|null $hydrator
     * @return HydratorInterface
     */
    public function getHydrator($hydrator = null)
    {
        $hydrator           = ($hydrator) ?: $this->serviceAlias;
        $sl                 = $this->getServiceLocator();

        /* @var $hydratorManager HydratorPluginManager */
        $hydratorManager    = $sl->get('HydratorManager');
        $hydrator           = $hydratorManager->get($hydrator);

        return $hydrator;
    }

    /**
     * @param $argv
     * @return \ArrayObject
     */
    public function prepareEventArguments($argv)
    {
        /* @var $em EventManager */
        $em     = $this->getEventManager();
        $argv   = $em->prepareArgs($argv);
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
