<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Mapper
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */
namespace UthandoCommon\Mapper;

/**
 * Interface MapperInterface
 * @package UthandoCommon\Mapper
 */
interface MapperInterface
{
    public function getById($id);

    public function fetchAll();

    public function insert(array $data, $table = null);

    public function update(array $data, $where, $table = null);

    public function delete($where, $table = null);

    public function getPrimaryKey();

    public function getTable();
} 