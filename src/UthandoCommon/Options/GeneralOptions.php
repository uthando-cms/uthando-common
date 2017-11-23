<?php declare(strict_types=1);
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 23/11/17 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\Options;


use Zend\Stdlib\AbstractOptions;

class GeneralOptions extends AbstractOptions
{
    /**
     * @var bool
     */
    protected $ssl = false;

    /**
     * @var bool
     */
    protected $maintenanceMode = false;

    public function isSsl(): bool
    {
        return $this->ssl;
    }

    public function getSsl(): bool
    {
        return $this->isSsl();
    }

    public function setSsl(bool $ssl): GeneralOptions
    {
        $this->ssl = $ssl;
        return $this;
    }

    public function isMaintenanceMode(): bool
    {
        return $this->maintenanceMode;
    }

    public function getMaintenanceMode(): bool
    {
        return $this->isMaintenanceMode();
    }

    public function setMaintenanceMode(bool $maintenanceMode): GeneralOptions
    {
        $this->maintenanceMode = $maintenanceMode;
        return $this;
    }
}
