<?php declare(strict_types=1);
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 23/11/17 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\Form\Settings;


use TwbBundle\Form\View\Helper\TwbBundleForm;
use UthandoCommon\Options\GeneralOptions;
use Zend\Filter\Boolean;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Form\Element\Checkbox;
use Zend\Form\Fieldset;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilterProviderInterface;

class GeneralFieldSet extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods())
            ->setObject(new GeneralOptions());
    }

    public function init()
    {
        $this->add([
            'type' => Checkbox::class,
            'name' => 'ssl',
            'options' => [
                'label' => 'Enable SSL',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'use_hidden_element' => true,
                'required' => false,
                'checked_value' => '1',
                'unchecked_value' => '0',
                'column-size' => 'md-8 col-md-offset-4',
                'help-block' => 'Enable/Disable SSL.',
            ],
        ]);

        $this->add([
            'type' => Checkbox::class,
            'name' => 'maintenance_mode',
            'options' => [
                'label' => 'Maintenance Mode',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'use_hidden_element' => true,
                'required' => false,
                'checked_value' => '1',
                'unchecked_value' => '0',
                'column-size' => 'md-8 col-md-offset-4',
                'help-block' => 'Enable/Disable maintenance mode.',
            ],
        ]);
    }

    public function getInputFilterSpecification(): array
    {
        return [
            'ssl' => [
                'required' => true,
                'allow_empty' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                    ['name' => Boolean::class],
                ],
            ],
            'maintenance_mode' => [
                'required' => true,
                'allow_empty' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                    ['name' => Boolean::class],
                ],
            ],
        ];
    }
}
