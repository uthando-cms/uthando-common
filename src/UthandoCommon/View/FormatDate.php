<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\View
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */
namespace UthandoCommon\View;

use Zend\View\Helper\AbstractHelper;
use Zend\Soap\Exception\InvalidArgumentException;

/**
 * Class FormatDate
 * @package UthandoCommon\View
 */
class FormatDate extends AbstractHelper
{
    /**
     * @var string
     */
	protected $format = 'd/m/Y H:i:s';
	
	/**
	 * @var \DateTime
	 */
	protected $date;
	
	public function __invoke($date = null, $format = null)
	{
	    if ($date) {
	        $this->setDate($date);
	    }
	    
	    if ($format) {
	        $this->setFormat($format);
	    }
	    
        return $this;
	}
	
	public function __toString()
	{
	    return $this->render();
	}

    /**
     * @return string
     */
	public function render()
	{
	    if (!$this->date instanceof \DateTime) {
	        throw new InvalidArgumentException('You need to set the date format.');
	    }
	    return $this->getDate()->format($this->getFormat());
	}
	
	public function getFormat()
    {
        return $this->format;
    }

	public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

	public function getDate()
    {
        return $this->date;
    }

	public function setDate($date)
    {
        if (!$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }
        
        $this->date = $date;
        return $this;
    }

}
