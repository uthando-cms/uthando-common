<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Options
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2016 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Options;

use Zend\Cache\Storage\Adapter\AdapterOptions;
use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Cache\Storage\Adapter\FilesystemOptions;
use Zend\Cache\Storage\Plugin\Serializer;
use Zend\Stdlib\AbstractOptions;

/**
 * Class CacheOptions
 *
 * @package UthandoCommon\Options
 */
class CacheOptions extends AbstractOptions
{
    /**
     * @var array
     */
    public static $adapterOptionsMap = [
        Filesystem::class => FilesystemOptions::class,
    ];

    protected $enabled = false;

    /**
     * @var string
     */
    protected $adapter = Filesystem::class;

    /**
     * @var AdapterOptions
     */
    protected $options;

    /**
     * @var array
     */
    protected $plugins = [
        Serializer::class,
    ];

    public function getAdapterOptions($options): AdapterOptions
    {
        /** @var AdapterOptions $adapterOptions */
        $adapterOptions = new self::$adapterOptionsMap[$this->getAdapter()];
        $adapterOptions->setFromArray($options);

        return $adapterOptions;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): CacheOptions
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getAdapter(): string
    {
        return $this->adapter;
    }

    public function setAdapter(string $adapter): CacheOptions
    {
        $this->adapter = $adapter;
        return $this;
    }

    public function getOptions(): AdapterOptions
    {
        return $this->options;
    }

    public function setOptions(array $options): CacheOptions
    {
        $optionsClass = $this->getAdapterOptions($options);
        $this->options = $optionsClass;
        return $this;
    }

    public function getPlugins(): array
    {
        return $this->plugins;
    }

    public function setPlugins(array $plugins): CacheOptions
    {
        $this->plugins = $plugins;
        return $this;
    }
}
