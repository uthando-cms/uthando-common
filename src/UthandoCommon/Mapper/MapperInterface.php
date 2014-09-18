<?php
/**
 * Created by PhpStorm.
 * User: shaun
 * Date: 15/09/2014
 * Time: 18:35
 */

namespace UthandoCommon\Mapper;

use Zend\Stdlib\Hydrator\HydratorInterface;

interface MapperInterface
{
    public function getById($id);

    public function fetchAll();

    public function insert(array $data, $table = null);

    public function update(array $data, $where, $table = null);

    public function delete($where, $table = null);

    public function extract($dataOrModel, HydratorInterface $hydrator = null);

    public function getHydrator();

    public function getModel(array $data = null);
} 