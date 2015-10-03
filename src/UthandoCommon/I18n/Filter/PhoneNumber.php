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

namespace UthandoCommon\I18n\Filter;

use Zend\Filter\AbstractFilter;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;

/**
 * Class PhoneNumber
 *
 * @package UthandoCommon\I18n\Filter
 */
class PhoneNumber extends AbstractFilter
{
    /**
     * @var string
     */
    protected $country;

    /**
     * @var PhoneNumberUtil
     */
    protected $libPhoneNumber;

    public function __construct($options = [])
    {
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

    public function filter($value)
    {
        try {
            $NumberProto = $this->libPhoneNumber->parse($value, $this->getCountry());
        } catch (NumberParseException $e) {
            return $value;
        }

        return $this->libPhoneNumber->format($NumberProto, PhoneNumberFormat::E164);

    }
}
