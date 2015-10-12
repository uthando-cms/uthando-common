<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\I18n\View\Helper
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2015 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\I18n\View\Helper;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Zend\I18n\Exception\InvalidArgumentException;
use Zend\View\Helper\AbstractHelper;

/**
 * Class LibPhoneNumber
 *
 * @package UthandoCommon\I18n\View\Helper
 */
class LibPhoneNumber extends AbstractHelper
{
    /**
     * @var PhoneNumberUtil
     */
    protected $libPhoneNumber;

    /**
     * @var string
     */
    protected $regionCode;

    /**
     * @var string|PhoneNumber
     */
    protected $phoneNumber;

    /**
     * @var int
     */
    protected $format;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->libPhoneNumber = PhoneNumberUtil::getInstance();
    }

    /**
     * @param string|PhoneNumber $phoneNumber
     * @param string $regionCode
     * @param int $format
     * @return $this
     */
    public function __invoke($phoneNumber, $regionCode = PhoneNumberUtil::UNKNOWN_REGION, $format = PhoneNumberFormat::E164)
    {
        $this->setPhoneNumber($phoneNumber);
        $this->setRegionCode($regionCode);
        $this->setFormat($format);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $phoneNumber    = $this->getPhoneNumber();
        $format         = $this->getFormat();

        if (!$this->getPhoneNumber() instanceof PhoneNumber) {
            $phoneNumber = $this->parsePhoneNumber();
        }

        return $this->libPhoneNumber->format($phoneNumber, $format);
    }

    /**
     * @return PhoneNumber
     */
    public function parsePhoneNumber()
    {
        return $this->libPhoneNumber->parse($this->getPhoneNumber(), $this->getRegionCode());
    }

    /**
     * @return string|PhoneNumber
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|PhoneNumber $phoneNumber
     * @return $this
     */
    public function setPhoneNumber($phoneNumber)
    {
        if (!is_string($phoneNumber) && !$phoneNumber instanceof PhoneNumber) {
            throw new InvalidArgumentException('Phone format must be a string or instance of "libphonenumber\PhoneNumber".');
        }

        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string|int $format
     * @return $this
     */
    public function setFormat($format)
    {
        if (!is_int($format) && !is_string($format)) {
            throw new InvalidArgumentException('Phone format must be a integer or string.');
        }

        if (is_string($format)) {
            $format = strtolower($format);

            switch ($format) {
                case 'national':
                    $this->format = PhoneNumberFormat::NATIONAL;
                    break;
                case 'international':
                    $this->format = PhoneNumberFormat::INTERNATIONAL;
                    break;
                case 'rfc3966':
                    $this->format = PhoneNumberFormat::RFC3966;
                    break;
                case 'e164':
                default:
                    $this->format = PhoneNumberFormat::E164;
            }
            return $this;
        }

        if ((0 <= $format) && ($format <= 3)) {
            $this->format = $format;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getRegionCode()
    {
        return $this->regionCode;
    }

    /**
     * @param string $regionCode
     * @return $this
     */
    public function setRegionCode($regionCode)
    {
        $regionCode = (string) $regionCode;

        if (!in_array($regionCode, $this->libPhoneNumber->getSupportedRegions())) {
            throw new InvalidArgumentException('Region code "' . $regionCode . '" is not currently supported by libPhoneNumber.');
        }

        $this->regionCode = $regionCode;
        return $this;
    }
}
