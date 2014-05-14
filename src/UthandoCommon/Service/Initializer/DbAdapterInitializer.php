<?php
namespace UthandoCommon\Service\Initializer;

use UthandoCommon\Mapper\DbAdapterAwareInterface;
use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DbAdapterInitializer implements InitializerInterface
{
    protected $sqliteContraints = false;
    
	public function initialize($instance, ServiceLocatorInterface $serviceLocator)
	{
		if ($instance instanceof DbAdapterAwareInterface) {
			/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
			$dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
			
			$config = $serviceLocator->get('config');
			
			// enable foreign key contraints on sqlite.
			if (isset($config['db']['sqlite_contraints']) && !$this->sqliteContraints) {
			    $dbAdapter->query('PRAGMA FOREIGN_KEYS = ON', Adapter::QUERY_MODE_EXECUTE);
			    $this->sqliteContraints = true;
			}
			
			$instance->setDbAdapter($dbAdapter);
		}
	}
}
