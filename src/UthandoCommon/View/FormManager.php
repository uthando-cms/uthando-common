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

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Class FromManager
 * @package UthandoCommon\View
 */
class FormManager extends AbstractViewHelper
{
    /**
     * @param $form
     * @param array $options
     * @return Form|Element
     */
    public function __invoke($form, $options = [])
    {
        $formManager = $this->getServiceLocator()
            ->getServiceLocator()
            ->get('FormElementManager');

        $form = $formManager->get($form, $options);

        return $form;
    }
}
