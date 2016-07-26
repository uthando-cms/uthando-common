<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\I18n\Validator;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;
use Zend\Validator\AbstractValidator;

/**
 * Class PhoneNumber
 *
 * @package UthandoCommon\I18n\Validator
 */
class PhoneNumber extends AbstractValidator
{
    const NO_MATCH = 'phoneNumberNoMatch';
    const UNSUPPORTED = 'phoneNumberUnsupported';
    const INVALID = 'phoneNumberInvalid';
    const INVALID_NUMBER = 'phoneNumberInvalidNumber';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NO_MATCH => 'The input does not match a phone number format.',
        self::UNSUPPORTED => 'The country provided is currently unsupported',
        self::INVALID => 'Invalid type given. String expected',
        self::INVALID_NUMBER => 'The number provided is an invalid number format',
    ];

    /**
     * @var string
     */
    protected $country;

    /**
     * @var PhoneNumberUtil
     */
    protected $libPhoneNumber;

    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->libPhoneNumber = PhoneNumberUtil::getInstance();

        if (array_key_exists('country', $options)) {
            $this->setCountry($options['country']);
        }
    }

    /**
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function isValid($value)
    {
        if (!is_scalar($value)) {
            $this->error(self::INVALID);
            return false;
        }

        $country            = $this->getCountry();
        $supportedCountries = $this->libPhoneNumber->getSupportedRegions();

        if (!in_array($country, $supportedCountries)) {
            $this->error(self::UNSUPPORTED);
            return false;
        }

        try {
            $numberProto = $this->libPhoneNumber->parse($value, $country);
        } catch (NumberParseException $e) {
            $this->error(self::INVALID_NUMBER);
            return false;
        }

        if (!$this->libPhoneNumber->isValidNumber($numberProto)) {
            $this->error(self::INVALID_NUMBER);
            return false;
        }

        $region = $this->libPhoneNumber->getRegionCodeForNumber($numberProto);

        if ($this->libPhoneNumber->isValidNumberForRegion($numberProto, $region)) {
            return true;
        }

        $this->error(self::NO_MATCH);

        return false;
    }
}
