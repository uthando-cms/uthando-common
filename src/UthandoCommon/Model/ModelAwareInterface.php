<?php

namespace UthandoCommon\Model;

use Zend\Stdlib\Hydrator\HydratorInterface;

interface ModelAwareInterface
{
    /**
     * @param null $data
     * @return ModelInterface
     */
    public function getModel($data = null);

    /**
     * @param ModelInterface $model
     * @return ModelInterface
     */
    public function setModel(ModelInterface $model);

    /**
     * @param $dataOrModel
     * @param HydratorInterface $hydrator
     * @return array
     */
    public function extract($dataOrModel, HydratorInterface $hydrator = null);
} 