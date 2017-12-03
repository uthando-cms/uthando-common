<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 25/09/17 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\Form\Settings\Cache;

use TwbBundle\Form\View\Helper\TwbBundleForm;
use Zend\Filter\Boolean;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Number;
use Zend\Form\Element\Text;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

abstract class BaseOptionsFieldSet extends Fieldset implements InputFilterProviderInterface
{
    public function init()
    {
        $this->add([
            'type' => Number::class,
            'name' => 'ttl',
            'options' => [
                'label' => 'Time To Live',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'help-block' => 'Time to live.',
            ],
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'namespace',
            'options' => [
                'label' => 'Namespace',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'help-block' => 'The “namespace” in which cache items will live.',
            ],
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'key_pattern',
            'options' => [
                'label' => 'Key Pattern',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'help-block' => 'Pattern against which to validate cache keys.',
            ],
        ]);

        $this->add([
            'type' => Checkbox::class,
            'name' => 'readable',
            'options' => [
                'label' => 'Readable',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'use_hidden_element' => true,
                'required' => false,
                'checked_value' => '1',
                'unchecked_value' => '0',
                'column-size' => 'md-8 col-md-offset-4',
                'help-block' => 'Enable/Disable reading data from cache.',
            ],
        ]);

        $this->add([
            'type' => Checkbox::class,
            'name' => 'writable',
            'options' => [
                'label' => 'Writable',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'use_hidden_element' => true,
                'required' => false,
                'checked_value' => '1',
                'unchecked_value' => '0',
                'column-size' => 'md-8 col-md-offset-4',
                'help-block' => 'Enable/Disable writing data to cache.',
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'ttl' => [
                'required' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                    ['name' => ToInt::class],
                ],
            ],
            'namespace' => [
                'required' => true,
                'allow_empty' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                ],
            ],
            'key_pattern' => [
                'required' => false,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                ],
            ],
            'readable' => [
                'required' => true,
                'allow_empty' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                    ['name' => Boolean::class],
                ],
            ],
            'writable' => [
                'required' => true,
                'allow_empty' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                    ['name' => Boolean::class, 'options' => [
                        'type' => Boolean::TYPE_ZERO_STRING,
                    ]],
                ],
            ],
        ];
    }
}
