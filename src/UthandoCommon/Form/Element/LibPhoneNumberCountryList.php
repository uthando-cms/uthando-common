<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Form\Element
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2015 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Form\Element;

use libphonenumber\PhoneNumberUtil;
use Zend\Form\Element\Select;

/**
 * Class LibPhoneNumberCountryList
 *
 * @package UthandoCommon\Form\Element
 */
class LibPhoneNumberCountryList extends Select
{
    /**
     * @var string
     */
    protected $emptyOption = '---Please select a country---';

    /**
     * set up option list
     */
    public function init()
    {
        $libPhoneNumber = PhoneNumberUtil::getInstance();
        $optionsList    = [];

        foreach ($libPhoneNumber->getSupportedRegions() as $code) {
            $fullTextCountry = \Locale::getDisplayRegion('en_' . $code);
            $optionsList[$code] = $fullTextCountry;
        }

        asort($optionsList);

        $this->setValueOptions($optionsList);
    }
}
