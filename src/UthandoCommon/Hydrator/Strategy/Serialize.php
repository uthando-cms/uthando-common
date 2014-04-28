<?php

namespace UthandoCommon\Hydrator\Strategy;

use Zend\Serializer\Serializer;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class Serialize implements  StrategyInterface
{
    public function extract($value)
    {
        return Serializer::serialize($value);
    }
    
    public function hydrate($value)
    {
        return Serializer::unserialize($value);
    }
}
