<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Event
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Event;

use UthandoCommon\Service\AbstractService;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

/**
 * Class ServiceListener
 *
 * @package UthandoCommon\Event
 */
class ServiceListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $events = $events->getSharedManager();

        $this->listeners[] = $events->attach(
            AbstractService::class,
            'pre.edit',
            [$this, 'edit']
        );
    }

    /**
     * @param Event $e
     */
    public function edit(Event $e)
    {
        /* @var $model \UthandoCommon\Model\ModelInterface */
        $model = $e->getParam('model');

        if ($model->has('dateModified')) {
            $model->setDateModified();
        }

    }
}
