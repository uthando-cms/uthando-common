<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Mvc\Controller
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2016 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Mvc\Controller;

use UthandoCommon\Controller\SettingsTrait;
use UthandoCommon\Form\Settings\CommonSettings;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class Settings
 *
 * @package UthandoCommon\Mvc\Controller
 */
class Settings extends AbstractActionController
{
    use SettingsTrait;

    public function __construct()
    {
        $this->setFormName(CommonSettings::class)
            ->setConfigKey('uthando_common');
    }
}
