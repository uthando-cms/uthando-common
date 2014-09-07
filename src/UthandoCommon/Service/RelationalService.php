<?php
namespace UthandoCommon\Service;

use Zend\Db\ResultSet\AbstractResultSet;

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
     * @param bool $children
     * @return mixed
     */
    public function populate($model, $children = false)
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

                if ($childModel instanceof AbstractResultSet) {
                    $childModel = $childModel->toArray();
                } elseif (is_array($childModel)) {
                    $childModel = $service->getMapper()->getModel($childModel);
                }

                $model->$setMethod($childModel);
            }
        }

        return $model;
    }

    /**
     * @param $name
     * @return object
     * @throws ServiceException
     */
    public function getRelatedService($name)
    {
        $map = $this->getReferenceMap();

        if (!array_key_exists($name, $map)) {
            throw new ServiceException($name . ' is not related service');
        }

        $sl = $this->getServiceLocator();

        return $sl->get($map[$name]['refClass']);
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
