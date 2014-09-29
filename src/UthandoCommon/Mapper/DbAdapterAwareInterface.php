<?php
namespace UthandoCommon\Mapper;

use Zend\Db\Adapter\Adapter;

interface DbAdapterAwareInterface
{
    /**
     * @return Adapter
     */
	public function getAdapter();

    /**
     * @param Adapter $dbAdapter
     * @return $this
     */
	public function setDbAdapter(Adapter $dbAdapter);
}
