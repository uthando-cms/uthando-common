<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Event
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2016 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Event;

use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\ResponseSender\AbstractResponseSender;
use Zend\Mvc\ResponseSender\SendResponseEvent;

/**
 * Class SendResponse
 *
 * @package UthandoCommon\Event
 */
class TidyResponseSender extends AbstractResponseSender
{
    /**
     * Tidy config array
     *
     * @var array
     */
    protected $config = [];

    /**
     * Send content
     *
     * @param  SendResponseEvent $event
     * @return $this
     */
    public function sendContent(SendResponseEvent $event)
    {
        if ($event->contentSent()) {
            return $this;
        }

        $response = $event->getResponse();

        $tidy = new \tidy();
        $tidy->parseString($response->getContent(), $this->config);
        //$tidy->cleanRepair();

        echo $tidy;

        $event->setContentSent();

        return $this;
    }

    /**
     * TidyResponseSender constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param SendResponseEvent $event
     * @return $this
     */
    public function __invoke(SendResponseEvent $event)
    {
        $response = $event->getResponse();

        if (!class_exists('tidy') || !$response instanceof Response || $response->getHeaders()->count() > 0) {
            return $this;
        }

        $this->sendHeaders($event)
            ->sendContent($event);

        $event->stopPropagation(true);

        return $this;
    }
}
