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

use DateTime;

/**
 * Class DateModifiedTrait
 *
 * @package UthandoCommon\Model
 */
trait DateModifiedTrait
{
    /**
     * @var DateTime
     */
    protected $dateModified;

    /**
     * @return DateTime $dateModified
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * @param DateTime $dateModified
     * @return $this
     */
    public function setDateModified(DateTime $dateModified = null)
    {
        $this->dateModified = $dateModified;
        return $this;
    }
}
