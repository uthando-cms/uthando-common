<?php
namespace UthandoCommon\Service;

use UthandoCommon\Mapper\AbstractMapper;

abstract class RelationalService extends AbstractService
{
    /**
     * @var array
     */
    protected $referenceMap;

    /**
     * populate relational records.
     *
     * @param $model \UthandoCommon\Model\Model
     * @param bool|array $children
     * @return mixed
     */
    public function populate($model, $children)
    {
        $allChildren = ($children === true) ? true : false;
        $children = (is_array($children)) ? $children : [];

        foreach ($this->getReferenceMap() as $name => $options) {
            if ($allChildren || in_array($name, $children)) {
                $sl = $this->getServiceLocator();
                /* @var $service \UthandoCommon\Service\AbstractService */
                $service = $sl->get($options['refClass']);

                $getIdMethod = 'get' .  ucfirst($options['refCol']);
                $setMethod = 'set' . ucfirst($name);

                $childModel = $service->getById($model->$getIdMethod());

                $model->$setMethod($childModel);
            }
        }

        return $model;
    }

    /**
     * @param $name
     * @return AbstractMapper
     * @throws ServiceException
     */
    public function getRelatedService($name)
    {
        $map = $this->getReferenceMap();

        if (!array_key_exists($name, $map)) {
            throw new ServiceException($name . ' is not related service');
        }

        if (!array_key_exists($name, $this->mappers)) {
            $this->setMapper($map[$name]['refClass']);
        }

        return $this->mappers[$map[$name]['refClass']];
    }

    /**
     * @return array
     */
    public function getReferenceMap()
    {
        return $this->referenceMap;
    }

    /**
     * @param $referenceMap
     * @return $this
     */
    public function setReferenceMap($referenceMap)
    {
        $this->referenceMap = (array) $referenceMap;
        return $this;
    }
}
