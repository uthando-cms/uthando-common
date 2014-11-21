<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Model
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */
namespace UthandoCommon\Model;

/**
 * Interface NestedSetInterface
 * @package UthandoCommon\Model
 */
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