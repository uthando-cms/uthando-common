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
class Settings extends Form
{
    public function init()
    {
        $this->add([
            'type' => DbFieldSet::class,
            'name' => 'db_options',
            'attributes' => [
                'class' => 'col-sm-6',
            ],
            'options' => [
                'label' => 'Database Options',
            ],
        ]);
    }
}
