<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 2017 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class AkismetOptions
 *
 * @package UthandoCommon\Options
 */
class AkismetOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $blog;

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return AkismetOptions
     */
    public function setApiKey(string $apiKey): AkismetOptions
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getBlog(): string
    {
        return $this->blog;
    }

    /**
     * @param string $blog
     * @return AkismetOptions
     */
    public function setBlog(string $blog): AkismetOptions
    {
        $this->blog = $blog;
        return $this;
    }
}