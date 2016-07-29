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
    /**
     * @var string
     */
    protected $extractFormat = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    protected $hydrateFormat = 'Y-m-d H:i:s';

    /**
     * DateTime constructor.
     *
     * @param array $options
     */
    function __construct($options = [])
    {
        if (isset($options['extractFormat'])) {
            $this->extractFormat = $options['extractFormat'];
        }

        if (isset($options['hydrateFormat'])) {
            $this->extractFormat = $options['hydrateFormat'];
        }
    }

    /**
     * @return string
     */
    public function getExtractFormat()
    {
        return $this->extractFormat;
    }

    /**
     * @param string $extractFormat
     * @return $this
     */
    public function setExtractFormat($extractFormat)
    {
        $this->extractFormat = $extractFormat;
        return $this;
    }

    /**
     * @return string
     */
    public function getHydrateFormat()
    {
        return $this->hydrateFormat;
    }

    /**
     * @param string $hydrateFormat
     * @return $this
     */
    public function setHydrateFormat($hydrateFormat)
    {
        $this->hydrateFormat = $hydrateFormat;
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

        return $value->format($this->getExtractFormat());
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
        }

        if (!$value instanceof DateTimeClass) {
            $date       = DateTimeClass::createFromFormat($this->getHydrateFormat(), $value);
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
                throw new Exception(sprintf(
                    '%s Date format: "%s", Date string: "%s". In class ' . __CLASS__,
                    $messages,
                    $this->getHydrateFormat(), $value)
                );
            }
        }

        return ($date instanceof DateTimeClass) ? $date : $value;
    }
}
