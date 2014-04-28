<?php
namespace UthandoCommon\Service\Factory;

use BjyProfiler\Db\Profiler\Profiler;
use BjyProfiler\Db\Adapter\ProfilingAdapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DbProfilerAdapterServiceFactory implements FactoryInterface
{
    /**
     * Create db adapter service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ProfilingAdapter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $dbParams = $config['db'];
        
        $adapter = new ProfilingAdapter($dbParams);
        
        $adapter->setProfiler(new Profiler);
        $adapter->injectProfilingStatementPrototype();
        return $adapter;
    }
}
