<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 15/09/17 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

class CssMin
{
    public function minify($content, $filters, $plugins)
    {
        // remove comments, tabs, spaces, newlines, etc.
        $search = array(
            "/\/\*(.*?)\*\/|[\t\r\n]/s" => "",
            "/ +\{ +|\{ +| +\{/" => "{",
            "/ +\} +|\} +| +\}/" => "}",
            "/ +: +|: +| +:/" => ":",
            "/ +; +|; +| +;/" => ";",
            "/ +, +|, +| +,/" => ","
        );
        $buffer = preg_replace(array_keys($search), array_values($search), $content);
        return $buffer;
    }
}
