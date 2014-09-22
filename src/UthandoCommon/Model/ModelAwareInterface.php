<?php

namespace UthandoCommon\Model;

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
} 