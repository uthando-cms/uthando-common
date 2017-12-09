<?php declare(strict_types=1);
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 07/12/17 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\Hydrator\Strategy;

use Zend\Hydrator\Strategy\StrategyInterface;
use Zend\Stdlib\AbstractOptions;

class OptionsStrategy implements StrategyInterface
{
    public function extract($value)
    {
        if ($value instanceof AbstractOptions) {
            $value = $value->toArray();
        }

        return $value;
    }

    public function hydrate($value)
    {
        return $value;
    }
}
