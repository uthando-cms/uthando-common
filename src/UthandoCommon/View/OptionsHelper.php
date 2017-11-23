<?php declare(strict_types=1);
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 22/11/17 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\View;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\Exception\InvalidArgumentException;
use Zend\View\Helper\AbstractHelper;
use Zend\View\HelperPluginManager;

class OptionsHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var AbstractOptions
     */
    protected $options;

    public function __invoke(string $options)
    {
        $sl = $this->getPluginManager();

        if ($sl->has($options)) {
            $this->setOptions($sl->get($options));
        } else {
            throw new InvalidArgumentException(printf('No option class found using name %s', $options));
        }

        return $this;
    }

    public function get(string $option)
    {
        if (isset($this->getOptions()->$option)) {
            return $this->getOptions()->$option;
        } else {
            throw new InvalidArgumentException(printf('No option called "%s" in class %s', $option, get_class($this->getOptions())));
        }
    }

    /**
     * @return AbstractOptions
     */
    public function getOptions(): AbstractOptions
    {
        return $this->options;
    }

    /**
     * @param AbstractOptions $options
     * @return OptionsHelper
     */
    public function setOptions(AbstractOptions $options): OptionsHelper
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return HelperPluginManager
     */
    public function getPluginManager(): HelperPluginManager
    {
        return $this->getServiceLocator()->getServiceLocator();
    }
}
