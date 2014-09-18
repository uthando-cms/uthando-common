<?php

return [
    'factories' => [
        'UthandoMapperManager'                      => 'UthandoCommon\Service\MapperManagerFactory',
        'UthandoModelManager'                       => 'UthandoCommon\Service\ModelManagerFactory',
        'Zend\Db\Adapter\Adapter'                   => 'Zend\Db\Adapter\AdapterServiceFactory',
        'Zend\Cache\Service\StorageCacheFactory'    => 'Zend\Cache\Service\StorageCacheFactory',
    ],
    'initializers' => [
        'UthandoCommon\Service\CacheStorageInitializer' => 'UthandoCommon\Service\Initializer\CacheStorageInitializer'
    ],
];