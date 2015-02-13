<?php
namespace UthandoCommon\Filter;

use Zend\Filter\AbstractFilter;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;

class PhoneNumber extends AbstractFilter
{
    /**
     * @var string
     */
    protected $country;
    
    /**
     * @var PhoneNumberUtil
     */
    protected $libPhoneNumer;

    public function __construct($options = [])
    {
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
    
    public function filter($value)
    {
        try {
            $NumberProto = $this->libPhoneNumer->parse($value, $this->getCountry());
        } catch (NumberParseException $e) {
            return $value;
        }
        
        return $this->libPhoneNumer->format($NumberProto, PhoneNumberFormat::E164);
        
    }
}
