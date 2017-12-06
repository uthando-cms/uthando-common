<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Controller
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Controller;

use UthandoCommon\Service\ServiceTrait;
use Zend\Filter\Word\UnderscoreToDash;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\Http\PhpEnvironment\Response;
use Zend\Config\Writer\PhpArray;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Mvc\Controller\Plugin\PostRedirectGet;
use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\ArrayUtils;

/**
 * Class SettingsTrait
 *
 * @package UthandoCommon\Controller
 * @method array|PostRedirectGet prg()
 * @method FlashMessenger flashMessenger()
 */
trait SettingsTrait
{
    use ServiceTrait;

    /**
     * @var string
     */
    protected $formName;

    /**
     * @var string
     */
    protected $configKey;

    /**
     * @return array
     */
    public function indexAction()
    {
        /* @var $form Form */
        $form = $this->getService('FormElementManager')
            ->get($this->getFormName());

        $prg = $this->prg();

        $config = $this->getService('config');
        $settings = $config[$this->getConfigKey()] ?? [];

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false === $prg) {
            $defaults = $settings;

            foreach ($settings as $key => $value) {
                // this needs moving to the form to set defaults there.
                if ($form->has($key) && $form->get($key) instanceof Fieldset) {
                    $object         = $form->get($key)->getObject();
                    $hydrator       = $form->get($key)->getHydrator();
                    $object         = $hydrator->hydrate($defaults[$key], $object);
                    $defaults[$key] = $hydrator->extract($object);
                }
            }

            $form->setData($defaults);

            return ['form' => $form,];
        }

        $form->setData($prg);

        if ($form->isValid()) {

            $arrayOrObject = $form->getData();


            if (is_array($arrayOrObject)) {
                unset($arrayOrObject['button-submit']);

                foreach ($arrayOrObject as $key => $value) {
                    // this needs moving to the form to set defaults there.
                    if ($form->has($key) && $form->get($key) instanceof Fieldset) {
                        $object                 = $form->get($key)->getObject();
                        $hydrator               = $form->get($key)->getHydrator();
                        $object                 = $hydrator->hydrate($arrayOrObject[$key], $object);
                        $arrayOrObject[$key]    = $object->toArray();
                    }
                }
            }

            if ($arrayOrObject instanceof AbstractOptions) {
                $arrayOrObject = $arrayOrObject->toArray();
            }

            $filter = new UnderscoreToDash();
            $fileName = $filter->filter($this->getConfigKey());

            $config = new PhpArray();
            $config->setUseBracketArraySyntax(true);

            $config->toFile('./config/autoload/' . $fileName . '.local.php', [$this->getConfigKey() => $arrayOrObject]);

            $appConfig = $this->getService('Application\Config');

            // delete cached config.
            if (true === $appConfig['module_listener_options']['config_cache_enabled']) {
                $configCache = $appConfig['module_listener_options']['cache_dir'] . '/module-config-cache.' . $appConfig['module_listener_options']['config_cache_key'] . '.php';
                if (file_exists($configCache)) {
                    unlink($configCache);
                }
            }

            $this->flashMessenger()->addSuccessMessage('Settings have been updated!');
        } else {
            $this->flashMessenger()->addErrorMessage('There is a wrong setting, please check all values are corect!');
        }

        return ['form' => $form,];
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return $this->formName;
    }

    /**
     * @param string $formName
     * @return $this
     */
    public function setFormName($formName)
    {
        $this->formName = $formName;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfigKey()
    {
        return $this->configKey;
    }

    /**
     * @param string $configKey
     * @return $this
     */
    public function setConfigKey($configKey)
    {
        $this->configKey = $configKey;
        return $this;
    }
}
