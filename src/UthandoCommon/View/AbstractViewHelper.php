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

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class AbstractViewHelper
 *
 * @package UthandoCommon\View
 * @method PhpRenderer getView()
 */
class AbstractViewHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ConfigTrait;
}
