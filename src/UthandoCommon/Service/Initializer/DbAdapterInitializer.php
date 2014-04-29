<?php
namespace UthandoCommon\Service\Initializer;

use UthandoCommon\Mapper\DbAdapterAwareInterface;
use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DbAdapterInitializer implements InitializerInterface
{
	public function initialize($instance, ServiceLocatorInterface $serviceLocator)
	{
		if ($instance instanceof DbAdapterAwareInterface) {
			/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
			$dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
			
			$config = $serviceLocator->get('config');
			
			// enable foreign key contraints on sqlite.
			if ($config['db']['sqlite_contraints']) {
			    $dbAdapter->query('PRAGMA FOREIGN_KEYS = ON', Adapter::QUERY_MODE_EXECUTE);
			}
			
			$instance->setDbAdapter($dbAdapter);
		}
	}
}
