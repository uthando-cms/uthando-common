<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommonTest\Stdlib
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2016 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommonTest\Stdlib;

use UthandoCommon\Stdlib\StringUtils;

class StringUtilsTest extends \PHPUnit_Framework_TestCase
{
    public function testEndsWith()
    {
        $string = 'A string the check';
        $check = StringUtils::endsWith($string, 'check');

        $this->assertTrue($check);
    }

    public function testStartsWith()
    {
        $string = 'A string the check';
        $check = StringUtils::startsWith($string, 'A');

        $this->assertTrue($check);
    }
}


