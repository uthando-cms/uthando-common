<?php

namespace UthandoCommon\Mapper;

interface MapperInterface
{
    public function getById($id);

    public function fetchAll();

    public function insert(array $data, $table = null);

    public function update(array $data, $where, $table = null);

    public function delete($where, $table = null);
} 