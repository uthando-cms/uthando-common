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

/**
 * Class Request
 *
 * @package UthandoCommon\View
 */
class Request extends AbstractViewHelper
{
    public function __invoke()
    {
        return $this->getServiceLocator()
            ->getServiceLocator()
            ->get('Request');
    }
}
