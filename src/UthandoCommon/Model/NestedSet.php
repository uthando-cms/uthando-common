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
 * Class NestedSet
 *
 * @package UthandoCommon\Model
 */
abstract class NestedSet implements ModelInterface, NestedSetInterface
{
    /**
     * @var int
     */
    protected $lft;

    /**
     * @var int
     */
    protected $rgt;

    /**
     * @var int
     */
    protected $depth;

    /**
     * @return int
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * @param $lft
     * @return $this
     */
    public function setLft(int $lft)
    {
        $this->lft = $lft;
        return $this;
    }

    /**
     * @return int
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * @param $rgt
     * @return $this
     */
    public function setRgt(int $rgt)
    {
        $this->rgt = $rgt;
        return $this;
    }

    /**
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @param $depth
     * @return $this
     */
    public function setDepth(int $depth)
    {
        $this->depth = $depth;
        return $this;
    }

    /**
     * @return int
     */
    public function width(): int
    {
        return ($this->getRgt() - $this->getLft()) + 1;
    }

    /**
     * returns true if there are children
     *
     * @return bool
     */
    public function hasChildren(): bool
    {
        $children = (($this->getRgt() - $this->getLft()) - 1) / 2;
        return (0 === $children) ? false : true;
    }
}
