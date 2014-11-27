<?php

return [
    'factories' => [
        'UthandoMapperManager'                      => 'UthandoCommon\Mapper\MapperManagerFactory',
        'UthandoModelManager'                       => 'UthandoCommon\Model\ModelManagerFactory',
        'UthandoServiceManager'                     => 'UthandoCommon\Service\ServiceManagerFactory',
        'Zend\Db\Adapter\Adapter'                   => 'Zend\Db\Adapter\AdapterServiceFactory',
        'Zend\Cache\Service\StorageCacheFactory'    => 'Zend\Cache\Service\StorageCacheFactory',
    ],
    'initializers' => [
        'UthandoCommon\Service\CacheStorageInitializer' => 'UthandoCommon\Service\Initializer\CacheStorageInitializer'
    ],
];