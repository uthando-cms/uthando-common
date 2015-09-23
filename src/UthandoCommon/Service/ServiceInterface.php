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

use UthandoCommon\Model\ModelInterface;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Interface ServiceInterface
 *
 * @package UthandoCommon\Service
 */
interface ServiceInterface
{
    /**
     * events to set up. This should be overridden in parent class.
     */
    public function attachEvents();

    /**
     * Gets Service Class
     *
     * @param $service
     * @return AbstractService
     */
    public function getService($service);

    /**
     * Sets Service Class
     *
     * @param $service
     * @return $this
     */
    public function setService($service);

    /**
     * @return array
     */
    public function getFormOptions();

    /**
     * @param array $formOptions
     */
    public function setFormOptions($formOptions);

    /**
     * Gets the default form for the service.
     *
     * @param ModelInterface $model
     * @param array $data
     * @param bool $useInputFilter
     * @param bool $useHydrator
     * @return Form $form
     */
    public function getForm(ModelInterface $model = null, array $data = null, $useInputFilter = false, $useHydrator = false);

    /**
     * Gets model from ModelManager
     *
     * @param null|string $model
     * @return ModelInterface
     */
    public function getModel($model = null);

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
}
