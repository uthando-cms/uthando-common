<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Form\Settings
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2016 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Form\Settings;

use UthandoCommon\Options\DbOptions;
use Zend\Form\Fieldset;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Class DbFieldSet
 *
 * @package UthandoCommon\Form\Settings
 */
class DbFieldSet extends Fieldset implements InputFilterProviderInterface
{
    /**
     * DbFieldSet constructor.
     *
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods())
            ->setObject(new DbOptions());
    }

    /**
     * init method.
     */
    public function init()
    {

    }

    /**
     * Get input filter for elements.
     *
     * @return array
     */
    public function getInputFilterSpecification(): array
    {
        return [

        ];
    }
}
