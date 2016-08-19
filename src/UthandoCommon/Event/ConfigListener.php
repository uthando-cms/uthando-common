<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Event
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2015 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Event;

use UthandoCommon\Config\ConfigInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\ModuleManager\ModuleEvent;
use Zend\Stdlib\ArrayUtils;

/**
 * Class ConfigListener
 *
 * @package UthandoCommon\Event
 */
class ConfigListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, [$this, 'onMergeConfig'], 1);
    }

    /**
     * @param ModuleEvent $event
     * @return ConfigListener
     */
    public function onMergeConfig(ModuleEvent $event)
    {
        $configListener     = $event->getConfigListener();
        $config             = $configListener->getMergedConfig(false);
        $loadedModules      = $event->getTarget()->getLoadedModules();
        $loadUthandoConfigs = (isset($config['load_uthando_configs'])) ? $config['load_uthando_configs'] : false;
        $uthandoConfig      = [];

        if (false === $loadUthandoConfigs) return $this;

        // get the configurations from each module
        // must return an array to merge
        foreach ($loadedModules as $module) {
            if ($module instanceof ConfigInterface) {
                $moduleConfig = $module->getUthandoConfig();
                $uthandoConfig = ArrayUtils::merge($uthandoConfig, $moduleConfig);
            }
        }

        $config = ArrayUtils::merge($config, $uthandoConfig);

        // Pass the changed configuration back to the listener:
        $configListener->setMergedConfig($config);

        return $this;
    }
}
