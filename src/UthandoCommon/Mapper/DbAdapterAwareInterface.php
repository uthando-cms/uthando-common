<?php
namespace UthandoCommon\Mapper;

use Zend\Db\Adapter\Adapter;

interface DbAdapterAwareInterface
{
	public function getAdapter();
	public function setDbAdapter(Adapter $dbAdapter);
}
