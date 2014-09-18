<?php

namespace UthandoCommon\Model;

use Zend\Mvc\Exception\InvalidPluginException;
use Zend\ServiceManager\AbstractPluginManager;

class ModelManager extends AbstractPluginManager
{
    /**
     * Don't share models by default
     *
     * @var bool
     */
    protected $shareByDefault = false;

    /**
     * Validate the plugin
     *
     * Checks that the Model is an instance of ModelInterface
     *
     * @param  mixed $plugin
     * @throws InvalidPluginException
     * @return void
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof ModelInterface) {
            return;
        }

        throw new InvalidPluginException(sprintf(
            'Plugin of type %s is invalid; must implement %s\Model\ModelInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
} 