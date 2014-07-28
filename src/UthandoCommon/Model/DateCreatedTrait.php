<?php
namespace UthandoCommon\Model;

use DateTime;

trait DateCreatedTrait
{
    /**
     * @var DateTime
     */
    protected $dateCreated;
    
    /**
     * @return DateTime $dateCreated
     */
    public function getDateCreated()
    {
    	return $this->dateCreated;
    }
    
    /**
     * @param DateTime $dateCreated
     */
    public function setDateCreated(DateTime $dateCreated = null)
    {
    	$this->dateCreated = $dateCreated;
    	return $this;
    }
}
