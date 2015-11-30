<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\View
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2015 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\View;

use UthandoCommon\Stdlib\PhpToJsDateFormat;
use Zend\View\Helper\AbstractHelper;

/**
 * Class PhpToJsDateFromat
 *
 * @package UthandoCommon\View
 */
class ConvertToJsDateFormat extends AbstractHelper
{
    /**
     * @var string
     */
    protected $format;

    /**
     * @param null $format
     * @return $this
     */
    public function __invoke($format = null)
    {
        if ($format) {
            $this->setFormat($format);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function renderJsFormat()
    {
        return PhpToJsDateFormat::convertPhpToJs($this->getFormat());
    }

    /**
     * @return string
     */
    public function renderPhpFormat()
    {
        return PhpToJsDateFormat::convertJsToPhp($this->getFormat());
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }
}
