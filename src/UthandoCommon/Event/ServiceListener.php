<?php
namespace UthandoCommon\Event;

use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

class ServiceListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $events = $events->getSharedManager();
        
		$this->listeners[] = $events->attach('UthandoCommon\Service\AbstractService', 'pre.edit', [$this, 'edit']);
    }
    
    public function edit(Event $e)
    {
        /* @var $model \UthandoCommon\Model\ModelInterface */
        $model = $e->getParam('model');
        
        if ($model->has('dateModified')) {
            $model->setDateModified();
        }
        
    }
}
