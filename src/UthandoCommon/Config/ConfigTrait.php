<?php declare(strict_types=1);
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Config
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2015 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Config;

use ReflectionClass;

trait ConfigTrait
{
    /**
     * Name of the module config directory
     */
    protected $configDirectory = 'config';

    /**
     * File pattern for uthando configs
     */
    protected $filePattern = 'uthando-*.config.php';

    /**
     * Get all uthando configs for this module.
     */
    public function getUthandoConfig(): array
    {
        $config             = [];
        $configFilePattern  = join('/', [
            $this->getModulePath(),
            $this->configDirectory,
            $this->filePattern,
        ]);

        foreach (glob($configFilePattern) as $filename) {
            /** @noinspection PhpIncludeInspection */
            $configFile = include $filename;
            $config     = array_merge($config, $configFile);
        }

        return $config;
    }

    /**
     * Get the directory the module is in.
     */
    public function getModulePath(): string
    {
        $reflector = new ReflectionClass(get_class($this));
        $fn = $reflector->getFileName();
        $directory = dirname($fn);
        return $directory;
    }
}
