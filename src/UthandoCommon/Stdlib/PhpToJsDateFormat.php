<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Stdlib
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2015 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Stdlib;

/**
 * Class PhpToJsDateTime
 *
 * @package UthandoCommon\Stdlib
 */
class PhpToJsDateFormat
{
    /**
     * array of php -> javascript date formats.
     *
     * @var array
     */
    public static $dateTokenMap = [
        /* Day Formats */
        'd' => 'DD', // Day of the month, 2 digits with leading zeros eg 01 to 31
        'D' => 'ddd', // A textual representation of a day, three letters eg Mon through Sun
        'j' => 'D', // Day of the month without leading zeros eg 1 to 31
        'l' => 'dddd', // A full textual representation of the day of the week eg Sunday through Saturday
        'S' => 'o', // English ordinal suffix for the day of the month, 2 characters eg st, nd, rd or th. Works well with D
        'z' => 'DDD', // The day of the year (starting from 0) eg 0 through 365

        /* Month Formats */
        'F' => 'MMMM', // A full textual representation of a month, such as January or March eg January through December
        'm' => 'MM', // Numeric representation of a month, with leading zeros eg 01 through 12
        'M' => 'MMM', // A short textual representation of a month, three letters eg Jan through Dec
        'n' => 'M', // Numeric representation of a month, without leading zeros eg 1 through 12

        /* Year Formats */
        'Y' => 'YYYY', // A full numeric representation of a year, 4 digits eg 1999 or 2003
        'y' => 'YY', // A two digit representation of a year eg 99 or 03

        /* Time Formats */
        'a' => 'a', // Ante meridiem in lowercase eg am or pm
        'A' => 'A', // Post meridiem in uppercase eg AM or PM
        'g' => 'h', // 12-hour format of an hour without leading zero eg 1 through 12
        'h' => 'hh', // 12-hour format of an hour with leading zero eg 01 through 12
        'G' => 'H', // 24-hour format of an hour without leading zero eg 0 through 23
        'H' => 'HH', // 24-hour format of an hour with leading zero eg 00 through 23
        'i' => 'mm', // Minutes with leading zeros eg 00 to 59
        's' => 'ss', // Seconds, with leading zeros eg 00 through 59
        'u' => 'SSSSSS', // Microseconds (up to six digits) eg 45, 654321

        /* Timezone Formats */
        'e' => 'zz', // Timezone identifier eg UTC, GMT, Atlantic/Azores
        'O' => 'ZZ', // Difference to Greenwich time (GMT) in hours eg Example: +0200
        'P' => 'Z', // Difference to Greenwich time (GMT) with colon between hours and minutes eg +02:00
        'T' => 'z', // Timezone abbreviation eg EST, MDT

        /* Full Date/Time Formats */
        'U' => 'X', // Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT) eg 1292177455
    ];

    /**
     * @param $format
     * @return mixed
     */
    public static function convertPhpToJs(string $format) : string
    {
        return strtr($format, self::$dateTokenMap);
    }

    /**
     * @param $format
     * @return mixed
     */
    public static function convertJsToPhp(string $format) : string
    {
        return strtr($format, array_flip(self::$dateTokenMap));
    }
}
