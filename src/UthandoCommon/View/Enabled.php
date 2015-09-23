<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\View
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\View;

use Zend\View\Helper\AbstractHelper;

/**
 * Class Enabled
 *
 * @package UthandoCommon\View
 */
class Enabled extends AbstractHelper
{
    /**
     * @param $model
     * @param $params
     * @return string
     */
    public function __invoke($model, $params)
    {
        $id = 'get' . ucfirst($params['table']) . 'Id';

        $url = $this->view->url($params['route'], [
            'action' => 'set-enabled',
            'id' => $model->$id()
        ]);

        $format = '<p class="' . $params['table'] . '-status"><a href="%s" class="glyphicon glyphicon-%s ' . $params['table'] . '-%s">&nbsp;</a></p>';

        if ($model->isEnabled()) {
            $icon = 'ok';
            $class = 'enabled';
        } else {
            $icon = 'remove';
            $class = 'disabled';
        }

        return sprintf($format, $url, $icon, $class);
    }
}
