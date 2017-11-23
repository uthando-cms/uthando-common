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

use UthandoCommon\Options\GeneralOptions;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Http\Request;
use Zend\Mvc\Application as MvcApplication;
use Zend\Mvc\MvcEvent;

/**
 * Class MvcListener
 *
 * @package UthandoCommon\Event
 */
class MvcListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'requireSsl'], -10000);
    }

    /**
     * @param MvcEvent $event
     * @return mixed
     */
    public function requireSsl(MvcEvent $event)
    {
        $application    = $event->getApplication();
        $options        = $application->getServiceManager()->get(GeneralOptions::class);
        $request        = $event->getRequest();
        $response       = $event->getResponse();
        $uri            = $request->getUri();

        if (false === $options->isSsl()) {
            return;
        }

        if (!$request instanceof Request) {
            return;
        }

        if ($event->isError() && $event->getError() === MvcApplication::ERROR_ROUTER_NO_MATCH) {
            // No matched route has been found - don't do anything
            return;
        }

        // only redirect to SSL if on HTTP
        if ('http' === $uri->getScheme()) {
            $uri->setScheme('https');
            return self::redirect($uri, $response);
        }

        return;
    }

    /**
     * @param $uri
     * @param $response
     * @return mixed
     */
    private function redirect($uri, $response)
    {
        $response->getHeaders()->addHeaderLine('Location', $uri->toString());
        $response->setStatusCode(302);

        return $response;
    }
}
