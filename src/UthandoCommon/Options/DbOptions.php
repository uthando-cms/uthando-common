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

use Zend\Stdlib\AbstractOptions;

/**
 * Class DbOptions
 *
 * @package UthandoCommon\Options
 */
class DbOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $driver;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $port;

    /**
     * @var string
     */
    protected $database;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var array
     */
    protected $driverOptions;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var bool
     */
    protected $sqliteConstraints = false;

    /**
     * @var bool
     */
    protected $mysql57Compatible = false;

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param string $driver
     * @return $this
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param string $port
     * @return $this
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param string $database
     * @return $this
     */
    public function setDatabase($database)
    {
        $this->database = $database;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return array
     */
    public function getDriverOptions()
    {
        return $this->driverOptions;
    }

    /**
     * @param array $driverOptions
     * @return $this
     */
    public function setDriverOptions($driverOptions)
    {
        $this->driverOptions = $driverOptions;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSqliteConstraints()
    {
        return $this->sqliteConstraints;
    }

    /**
     * @return bool
     */
    public function getSqliteConstraints()
    {
        return $this->isSqliteConstraints();
    }

    /**
     * @param boolean $sqliteConstraints
     * @return $this
     */
    public function setSqliteConstraints($sqliteConstraints)
    {
        $this->sqliteConstraints = $sqliteConstraints;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isMysql57Compatible()
    {
        return $this->mysql57Compatible;
    }

    /**
     * @return bool
     */
    public function getMysql57Compatible()
    {
        return $this->isMysql57Compatible();
    }

    /**
     * @param boolean $mysql57Compatible
     * @return $this
     */
    public function setMysql57Compatible($mysql57Compatible)
    {
        $this->mysql57Compatible = $mysql57Compatible;
        return $this;
    }
}
