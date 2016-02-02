<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Hydrator
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2015 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Hydrator;

use UthandoCommon\Model\ModelInterface;
use Zend\Hydrator\AbstractHydrator as ZendAbstractHydrator;
use Zend\Hydrator\NamingStrategy\MapNamingStrategy;

/**
 * Class BaseHydrator
 *
 * @package UthandoCommon\Hydrator
 */
class BaseHydrator extends ZendAbstractHydrator
{

    /**
     * Array map of object to database names
     *
     * <object_property> => <database_key>
     *
     * @var array
     */
    protected $map = [];

    /**
     * @var string
     */
    protected $tablePrefix;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setNamingStrategy(new MapNamingStrategy($this->map));
        $this->init();
    }

    /**
     * Method to use to add hydrator setup
     */
    public function init()
    {}

    /**
     * Extract values from an object
     *
     * @param  ModelInterface $object
     * @return array
     */
    public function extract($object)
    {
        $data = $object->getArrayCopy();
        $extractedData = [];

        foreach ($data as $key => $value) {
            $extractName = $this->extractName($key);

            if (array_key_exists($extractName, $this->map)) {
                $value = $this->extractValue($key, $value, $data);
                $extractedData[$extractName] = $value;
            }
        }

        return $extractedData;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  ModelInterface $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        foreach ($data as $key => $value) {

            $hydrateName = $this->hydrateName($key);

            if ($object->has($hydrateName)) {
                $method = 'set' . ucfirst($hydrateName);
                $value = $this->hydrateValue($hydrateName, $value, $data);
                $object->$method($value);
            }
        }

        return $object;
    }

    /**
     * @return array
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param array $map
     * @return $this
     */
    public function setMap($map)
    {
        $this->map = $map;
        return $this;
    }

    /**
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    /**
     * @param string $tablePrefix
     * @return $this
     */
    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix . '.';
        return $this;
    }
}
