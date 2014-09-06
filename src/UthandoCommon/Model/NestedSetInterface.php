<?php
namespace UthandoCommon\Model;


interface NestedSetInterface
{
    public function getLft();
    public function setLft($lft);
    public function getRgt();
    public function setRgt($rgt);
    public function getDepth();
    public function setDepth($depth);
    public function getWidth();
    public function setWidth($width);
    public function hasChildren();
} 