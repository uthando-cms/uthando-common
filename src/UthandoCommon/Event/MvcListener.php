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
    	$config = $event->getApplication()->getConfig();
    	
    	if (false === $config['uthando_common']['ssl']) {
    		return;
    	}
    	
        $request = $event->getRequest();
        
        if (!$request instanceof Request) {
        	return;
        }
        
        if ($event->isError() && $event->getError() === MvcApplication::ERROR_ROUTER_NO_MATCH) {
        	// No matched route has been found - don't do anything
        	return;
        }
    
    	$match     = $event->getRouteMatch();
    	$params    = $match->getParams();
    	
    	/**
    	 * If we have a route that defines 'force-ssl' prefer that instruction above
    	 * anything else and redirect if appropriate
    	 *
    	 * Possible values of 'force-ssl' param are:
    	 *   'ssl'      : Force SSL
    	 *   'http'     : Force Non-SSL
    	 */
    	if (isset($params['force-ssl'])) {
    		$force    = strtolower($params['force-ssl']);
    		$response = $event->getResponse();
    		$uri      = $request->getUri();
    		
    		if ('ssl' === $force && 'http' === $uri->getScheme()) {
    		    $uri->setScheme('https');
    		    return self::redirect($uri, $response);
    		}
    		
    		if ('http' === $force && 'https' === $uri->getScheme()) {
    			$uri->setScheme('http');
    			return self::redirect($uri, $response);
    		}
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
