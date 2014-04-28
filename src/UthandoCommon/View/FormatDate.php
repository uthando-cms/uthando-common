<?php

namespace UthandoCommon\View;

use Zend\View\Helper\AbstractHelper;
use DateTime;

/**
 * View Helper
 */
class FormatDate extends AbstractHelper
{
	protected $format = 'd/m/Y H:i:s';
	protected $date;
	
	public function __invoke($date = null)
	{
        $this->setDate($date);
        return $this;
	}
	
	public function __toString()
	{
	    return $this->render();
	}
	
	public function render()
	{
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
        if (!$date instanceof DateTime) {
            $date = new DateTime($date);
        }
        
        $this->date = $date;
        return $this;
    }

}
