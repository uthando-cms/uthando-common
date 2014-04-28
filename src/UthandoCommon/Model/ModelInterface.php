<?php
namespace UthandoCommon\Model;

interface ModelInterface
{
    /**
     * Check to see if this class has a getter method defined
     *
     * @param string $prop
     * @return boolean
     */
    public function has($prop);
    
    /**
     * Returns object properties as an array
     *
     * @return array:
     */
    public function getArrayCopy();
}
