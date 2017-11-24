<?php declare(strict_types=1);
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
use Zend\Hydrator\HydratorInterface;
use Zend\InputFilter\InputFilter;
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

    const EVENT_PRE_PREPARE_FORM    = 'pre.form';
    const EVENT_POST_PREPARE_FORM   = 'form.init';
    const EVENT_PRE_FORM_INIT       = 'pre.form.init';
    const EVENT_POST_FORM_INIT      = 'post.form.init';

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
     * @var $form
     */
    protected $form;

    /**
     * @var string
     */
    protected $hydrator;

    /**
     * @var string
     */
    protected $inputFilter;

    /**
     * @var string
     */
    protected $model;

    /**
     * events to set up. This should be overridden in parent class.
     */
    public function attachEvents() {}

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
        $this->getEventManager()->trigger(self::EVENT_PRE_PREPARE_FORM, $this, $argv);
        $data = $argv['data'];

        /* @var $form Form */
        $form = $this->getForm(null, $this->formOptions);

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
        $argv = $this->prepareEventArguments($argv);

        $this->getEventManager()->trigger(self::EVENT_POST_PREPARE_FORM, $argv);

        return $form;
    }

    /**
     * Gets the default form or on specified for the service.
     *
     * @param null|string $name
     * @param array $options
     * @return Form
     */
    public function getForm(string $name = null, array $options = []): Form
    {
        $name               = $name ?? $this->form ?? $this->serviceAlias;
        $sl                 = $this->getServiceLocator();
        $formElementManager = $sl->get('FormElementManager');
        $argv               = compact('options');
        $argv               = $this->prepareEventArguments($argv);

        $this->getEventManager()->trigger(self::EVENT_PRE_FORM_INIT, $this, $argv);

        /* @var $form Form */
        $form = $formElementManager->get($name, $options);
        $argv = compact('form', 'options');
        $argv = $this->prepareEventArguments($argv);

        $this->getEventManager()->trigger(self::EVENT_POST_FORM_INIT, $this, $argv);

        return $form;
    }

    /**
     * @return array
     */
    public function getFormOptions(): array
    {
        return $this->formOptions;
    }

    /**
     * @param array $formOptions
     * @return AbstractService
     */
    public function setFormOptions($formOptions): AbstractService
    {
        $this->formOptions = $formOptions;
        return $this;
    }

    /**
     * Gets model from ModelManager
     *
     * @param null|string $model
     * @return ModelInterface
     */
    public function getModel($model = null)
    {
        $model          = $model ?? $this->model ?? $this->serviceAlias;
        $sl             = $this->getServiceLocator();
        $modelManager   = $sl->get(ModelManager::class);
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
        $inputFilter        = $inputFilter ?? $this->inputFilter ?? $this->serviceAlias;
        $sl                 = $this->getServiceLocator();
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
        $hydrator           = $hydrator ?? $this->hydrator ?? $this->serviceAlias;
        $sl                 = $this->getServiceLocator();
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
