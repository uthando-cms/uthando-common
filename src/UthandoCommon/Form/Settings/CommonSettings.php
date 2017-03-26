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

use Zend\Form\Form;

/**
 * Class Settings
 *
 * @package UthandoCommon\Form\Settings
 */
class CommonSettings extends Form
{
    /**
     * Init
     */
    public function init()
    {
        $this->add([
            'type' => AkismetFieldSet::class,
            'name' => 'akismet',
            'attributes' => [
                'class' => 'col-sm-6',
            ],
            'options' => [
                'label' => 'Akismet',
            ],
        ]);
    }
}
