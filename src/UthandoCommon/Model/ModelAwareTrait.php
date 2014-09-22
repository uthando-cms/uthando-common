<?php

namespace UthandoCommon\Model;

trait ModelAwareTrait
{
    /**
     * @var ModelInterface
     */
    protected $model;

    /**
     * @param null $data
     * @return object|ModelInterface
     */
    public function getModel($data = null)
    {
        $model = clone $this->model;

        if ($data && method_exists($this, 'getHydrator')) {
            /** @var $hydrator \Zend\Stdlib\Hydrator\HydratorInterface */
            $hydrator = $this->getHydrator();
            $data = (array) $data;
            return $hydrator->hydrate($data, $model);
        }

        return $model;
    }

    /**
     * @param ModelInterface $model
     * @return $this
     */
    public function setModel(ModelInterface $model)
    {
        $this->model = $model;
        return $this;
    }
} 