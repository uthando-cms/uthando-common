<?php
namespace UthandoCommon\Model;


interface NestedSetInterface
{
    /**
     * @return int
     */
    public function getLft();

    /**
     * @param int $lft
     * @return $this
     */
    public function setLft($lft);

    /**
     * @return int
     */
    public function getRgt();

    /**
     * @param int $rgt
     * @return $this
     */
    public function setRgt($rgt);

    /**
     * @return int
     */
    public function getDepth();

    /**
     * @param int $depth
     * @return $this
     */
    public function setDepth($depth);

    /**
     * @return int
     */
    public function getWidth();

    /**
     * @param int $width
     * @return $this
     */
    public function setWidth($width);

    /**
     * @return bool
     */
    public function hasChildren();
} 