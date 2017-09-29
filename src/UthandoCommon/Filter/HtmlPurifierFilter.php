<?php declare(strict_types=1);
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 26/09/17 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\Filter;

use HTMLPurifier;
use Zend\Filter\AbstractFilter;

class HtmlPurifierFilter extends AbstractFilter
{
    /**
     * @var HTMLPurifier
     *
     */
    protected $instance;

    public function __construct(HTMLPurifier $htmlPurifier)
    {
        $this->instance = $htmlPurifier;
    }

    public function filter($value): string
    {
        return $this->instance->purify($value);
    }
}
