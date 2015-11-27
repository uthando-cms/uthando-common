<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Hydrator\Strategy
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Hydrator\Strategy;

use Exception;
use Zend\Hydrator\Strategy\StrategyInterface;
use DateTime as DateTimeClass;

/**
 * Class DateTime
 *
 * @package UthandoCommon\Hydrator\Strategy
 */
class DateTime implements StrategyInterface
{
    const ERROR_MEESAGE = '%s Date format: "%s", Date string: "%s". In class '. __CLASS__;
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
     * @param mixed $value
     * @return DateTimeClass|mixed|null
     * @throws Exception
     */
    public function hydrate($value)
    {
        $date = null;

        if (is_string($value) && '' === $value) {
            $value = null;
        } else {
            try {
                $date = new DateTimeClass($value);
            } catch (Exception $e) {
                $date       = DateTimeClass::createFromFormat($this->getDateFormat(), $value);
                $errors     = DateTimeClass::getLastErrors();
                $messages   = '';

                if ($errors['warning_count'] > 0) {
                    foreach ($errors['warnings'] as $index => $message) {
                        $messages .= $message . 'found at index ' . $index . '; ';
                    }
                }

                if ($errors['error_count'] > 0) {
                    foreach ($errors['errors'] as $index => $message) {
                        $messages .= $message . 'found at index ' . $index . '; ';
                    }
                }

                if ($messages) {
                    throw new Exception(sprintf(self::ERROR_MEESAGE, $messages, $this->getDateFormat(), $value));
                }
            }
        }

        return ($date instanceof DateTimeClass) ? $date : $value;
    }
}
