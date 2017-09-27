<?php declare(strict_types=1);
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 26/09/17 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\Filter\Service;

use HTMLPurifier;
use Interop\Container\ContainerInterface;
use Traversable;
use UthandoCommon\Filter\HtmlPurifierFilter;
use Zend\Filter\FilterPluginManager;
use Zend\ServiceManager\Exception\InvalidServiceException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HtmlPurifierFactory implements FactoryInterface
{
    /**
     * Options to pass to the constructor (when used in v2), if any.
     *
     * @param null|array
     */
    private $creationOptions = [];

    public function __construct($creationOptions = null)
    {
        if (null === $creationOptions) {
            return;
        }

        if ($creationOptions instanceof Traversable) {
            $creationOptions = iterator_to_array($creationOptions);
        }

        if (! is_array($creationOptions)) {
            throw new InvalidServiceException(sprintf(
                '%s cannot use non-array, non-traversable creation options; received %s',
                __CLASS__,
                (is_object($creationOptions) ? get_class($creationOptions) : gettype($creationOptions))
            ));
        }

        $this->creationOptions = $creationOptions;
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): HtmlPurifierFilter
    {
        /** @var FilterPluginManager $container */
        $config = $container->getServiceLocator()->get('config');

        $config = (isset($config['uthando_common']['html_purifier'])) ? $config['uthando_common']['html_purifier'] : [];

        if ($options) {
            $config = array_merge($config, $options);
        }

        $htmlPurifier = new HTMLPurifier($config);

        return new HtmlPurifierFilter($htmlPurifier);
    }

    public function createService(ServiceLocatorInterface $serviceLocator): HtmlPurifierFilter
    {
        return $this($serviceLocator, self::class, $this->creationOptions);
    }

    public function setCreationOptions(array $options)
    {
        $this->creationOptions = $options;
    }
}
