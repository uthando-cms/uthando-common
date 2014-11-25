<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Service
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */
namespace UthandoCommon\Service;

use Zend\Db\ResultSet\HydratingResultSet;

/**
 * Class AbstractRelationalMapperService
 * @package
 */
abstract class AbstractRelationalMapperService extends AbstractMapperService
{
    /**
     * @var array
     */
    protected $referenceMap;

    /**
     * @param array $post
     * @return \Zend\Db\ResultSet\HydratingResultSet|\Zend\Db\ResultSet\ResultSet|\Zend\Paginator\Paginator
     */
    public function search(array $post)
    {
        $models = parent::search($post);

        foreach ($models as $model) {
            $this->populate($model, true);
        }

        return $models;
    }

    /**
     * populate relational records.
     *
     * @param \UthandoCommon\Model\Model $model
     * @param bool|array $children
     * @return mixed
     */
    public function populate($model, $children)
    {
        $allChildren = ($children === true) ? true : false;
        $children = (is_array($children)) ? $children : [];

        foreach ($this->getReferenceMap() as $name => $options) {
            if ($allChildren || in_array($name, $children)) {

                $service = $this->getRelatedService($name);

                $getIdMethod = 'get' .  ucfirst($options['refCol']);
                $setMethod = 'set' . ucfirst($name);
                $getMethod = (isset($options['getMethod'])) ? $options['getMethod'] : 'getById';

                $childModel = $service->$getMethod($model->$getIdMethod(), $options['refCol']);

                if ($childModel instanceof HydratingResultSet) {
                    $childModelObjects = [];

                    foreach ($childModel as $row) {
                        $childModelObjects[] = $row;
                    }

                    $childModel = $childModelObjects;
                }

                $model->$setMethod($childModel);
            }
        }

        return $model;
    }

    /**
     * @param string $name
     * @return AbstractService
     * @throws ServiceException
     */
    public function getRelatedService($name)
    {
        $map = $this->getReferenceMap();

        if (!array_key_exists($name, $map)) {
            throw new ServiceException($name . ' is not related service');
        }

        return $this->getService($map[$name]['service']);
    }

    /**
     * @return array
     */
    public function getReferenceMap()
    {
        return $this->referenceMap;
    }

    /**
     * @param string|array $referenceMap
     * @return $this
     */
    public function setReferenceMap($referenceMap)
    {
        $this->referenceMap = (array) $referenceMap;
        return $this;
    }
}
