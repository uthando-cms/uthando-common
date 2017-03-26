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
    protected $driver = 'PDO_MYSQL';

    /**
     * @var string
     */
    protected $host = 'localhost';

    /**
     * @var string
     */
    protected $port = '3306';

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
    protected $driverOptions = [];

    /**
     * @var array
     */
    protected $options = [];

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
     * @param $driver
     * @return DbOptions
     */
    public function setDriver($driver): DbOptions
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param $host
     * @return DbOptions
     */
    public function setHost($host): DbOptions
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @param $port
     * @return DbOptions
     */
    public function setPort($port): DbOptions
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @param $database
     * @return DbOptions
     */
    public function setDatabase($database): DbOptions
    {
        $this->database = $database;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param $username
     * @return DbOptions
     */
    public function setUsername($username): DbOptions
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param $password
     * @return DbOptions
     */
    public function setPassword($password): DbOptions
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return array
     */
    public function getDriverOptions(): array
    {
        return $this->driverOptions;
    }

    /**
     * @param $driverOptions
     * @return DbOptions
     */
    public function setDriverOptions(array $driverOptions): DbOptions
    {
        $this->driverOptions = $driverOptions;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return DbOptions
     */
    public function setOptions(array $options): DbOptions
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSqliteConstraints(): bool
    {
        return $this->sqliteConstraints;
    }

    /**
     * @return bool
     */
    public function getSqliteConstraints(): bool
    {
        return $this->isSqliteConstraints();
    }

    /**
     * @param bool $sqliteConstraints
     * @return DbOptions
     */
    public function setSqliteConstraints(bool $sqliteConstraints): DbOptions
    {
        $this->sqliteConstraints = $sqliteConstraints;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isMysql57Compatible(): bool
    {
        return $this->mysql57Compatible;
    }

    /**
     * @return bool
     */
    public function getMysql57Compatible(): bool
    {
        return $this->isMysql57Compatible();
    }

    /**
     * @param bool $mysql57Compatible
     * @return DbOptions
     */
    public function setMysql57Compatible(bool $mysql57Compatible): DbOptions
    {
        $this->mysql57Compatible = $mysql57Compatible;
        return $this;
    }
}
