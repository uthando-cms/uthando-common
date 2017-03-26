<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 2017 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\Form\Settings;

use TwbBundle\Form\View\Helper\TwbBundleForm;
use UthandoCommon\Options\AkismetOptions;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Form\Element\Text;
use Zend\Form\Fieldset;
use Zend\Hydrator\ClassMethods;
use Zend\I18n\Validator\Alnum;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Uri\Http;
use Zend\Validator\Hostname;
use Zend\Validator\StringLength;
use Zend\Validator\Uri;

/**
 * Class AkismetFieldSet
 *
 * @package UthandoCommon\Form\Settings
 */
class AkismetFieldSet extends Fieldset implements InputFilterProviderInterface
{
    /**
     * AkismetFieldSet constructor.
     *
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods())
            ->setObject(new AkismetOptions());
    }

    /**
     * Init methods
     */
    public function init()
    {
        $this->add([
            'name' => 'api_key',
            'type' => Text::class,
            'options' => [
                'label' => 'API Key',
                'column-size' => 'md-8',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'help-block' => 'For an api key sign up to Akismet service here <a href="https://akismet.com" class="btn btn-primary btn-xs" target="_blank">https://akismet.com</a>',
            ],
        ]);

        $this->add([
            'name' => 'blog',
            'type' => Text::class,
            'options' => [
                'label' => 'Blog',
                'column-size' => 'md-8',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
            ],
        ]);
    }

    /**
     * Get input filter for elements.
     *
     * @return array
     */
    public function getInputFilterSpecification(): array
    {
        return [
            'api_key' => [
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    ['name' => StringLength::class, 'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 10,
                        'max'      => 20,
                    ]],
                    ['name' => Alnum::class],
                ],
            ],
            'blog' => [
                'required' => false,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    ['name' => StringLength::class, 'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 10,
                        'max'      => 255,
                    ]],
                    ['name' => Uri::class, 'options' => [
                        'uriHandler'    => Http::class,
                        'allowRelative' => false,
                    ]],
                ],
            ],
        ];
    }
}