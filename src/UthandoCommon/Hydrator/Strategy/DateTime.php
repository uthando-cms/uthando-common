<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Hydrator\Strategy
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Hydrator\Strategy;

use Zend\Hydrator\Strategy\StrategyInterface;
use DateTime as DateTimeClass;

/**
 * Class DateTime
 *
 * @package UthandoCommon\Hydrator\Strategy
 */
class DateTime implements StrategyInterface
{
    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * @param null|string $dateFormat
     */
    function __construct($dateFormat = null)
    {
        if ($dateFormat) {
            $this->dateFormat = $dateFormat;
        }
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * @param string $dateFormat
     * @return $this
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
        return $this;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function extract($value)
    {
        if (!$value instanceof DateTimeClass) {
            $value = new DateTimeClass();
        }

        return $value->format($this->dateFormat);
    }

    /**
     * @param string $value
     * @return \DateTime|null
     */
    public function hydrate($value)
    {
        if (is_string($value) && '' === $value) {
            $value = null;
        } else {
            $value = new DateTimeClass($value);
        }

        return $value;
    }
}
