<?php
namespace UthandoCommon\I18n\Validator;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;
use Zend\Validator\AbstractValidator;

class PhoneNumber extends AbstractValidator
{
    const NO_MATCH    = 'phoneNumberNoMatch';
    const UNSUPPORTED = 'phoneNumberUnsupported';
    const INVALID     = 'phoneNumberInvalid';
    
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NO_MATCH    => 'The input does not match a phone number format',
        self::UNSUPPORTED => 'The country provided is currently unsupported',
        self::INVALID     => 'Invalid type given. String expected',
    ];
    
    /**
     * @var string
     */
    protected $country;
    
    /**
     * @var PhoneNumberUtil
     */
    protected $libPhoneNumer;
    
    public function __construct($options = null)
    {
        parent::__construct($options);
        
        $this->libPhoneNumer = PhoneNumberUtil::getInstance();
    
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
        
        $country = $this->getCountry();
        
        $supportedCountries = $this->libPhoneNumer->getSupportedRegions();
        
        if (!in_array($country, $supportedCountries)) {
            $this->error(self::UNSUPPORTED);
            return false;
        }
        
        try {
            $NumberProto = $this->libPhoneNumer->parse($value, $country);
        } catch (NumberParseException $e) {
            $this->error(self::INVALID);
            return false;
        }
        
        if ($this->libPhoneNumer->isValidNumberForRegion($NumberProto, $country)) {
            return true;
        }
        
        $this->error(self::NO_MATCH);

        return false;
    }
}
