<?php
namespace UthandoCommon\Service\Initializer;

use UthandoCommon\Mapper\DbAdapterAwareInterface;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DbAdapterInitializer implements InitializerInterface
{
	public function initialize($instance, ServiceLocatorInterface $serviceLocator)
	{
		if ($instance instanceof DbAdapterAwareInterface) {
			/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
			$dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
			$instance->setDbAdapter($dbAdapter);
		}
	}
}
