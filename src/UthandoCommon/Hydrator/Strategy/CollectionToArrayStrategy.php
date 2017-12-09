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

use UthandoCommon\Model\AbstractCollection;
use UthandoCommon\Model\ModelInterface;
use Zend\Hydrator\Strategy\StrategyInterface;

class CollectionToArrayStrategy implements StrategyInterface
{
    public function extract($value)
    {
        $returnArray = [];
        if ($value instanceof AbstractCollection) {
            foreach ($value as $item) {
                if ($item instanceof ModelInterface) {
                    $returnArray[] = $item->getArrayCopy();
                }
            }
        }

        return $returnArray;
    }

    public function hydrate($value)
    {
        return $value;
    }
}
