<?php

return [
  'UthandoCommon\Module'                                    => __DIR__ . '/Module.php',
  
  'UthandoCommon\UthandoException'                          => __DIR__ . '/src/UthandoCommon/UthandoException.php',
  
  'UthandoCommon\Controller\AbstractCrudController'         => __DIR__ . '/src/UthandoCommon/Controller/AbstractCrudController.php',
  'UthandoCommon\Controller\SetExceptionMessages'           => __DIR__ . '/src/UthandoCommon/Controller/SetExceptionMessages.php',
  
  'UthandoCommon\Event\MvcListener'                         => __DIR__ . '/src/UthandoCommon/Event/MvcListener.php',
  
  'UthandoCommon\Filter\Slug'                               => __DIR__ . '/src/UthandoCommon/Filter/Slug.php',
  'UthandoCommon\Filter\Ucwords'                            => __DIR__ . '/src/UthandoCommon/Filter/Ucwords.php',
  
  'UthandoCommon\Hydrator\AbstractHydrator'                 => __DIR__ . '/src/UthandoCommon/Hydrator/AbstractHydrator.php',
  'UthandoCommon\Hydrator\Strategy\DateTime'                => __DIR__ . '/src/UthandoCommon/Hydrator/Strategy/DateTime.php',
  'UthandoCommon\Hydrator\Strategy\EmptyString'             => __DIR__ . '/src/UthandoCommon/Hydrator/Strategy/EmptyString.php',
  'UthandoCommon\Hydrator\Strategy\Null'                    => __DIR__ . '/src/UthandoCommon/Hydrator/Strategy/Null.php',
  'UthandoCommon\Hydrator\Strategy\Serialize'               => __DIR__ . '/src/UthandoCommon/Hydrator/Strategy/Serialize.php',
  'UthandoCommon\Hydrator\Strategy\TrueFalse'               => __DIR__ . '/src/UthandoCommon/Hydrator/Strategy/TrueFalse.php',
  
  'UthandoCommon\Mapper\AbstractMapper'                     => __DIR__ . '/src/UthandoCommon/Mapper/AbstractMapper.php',
  'UthandoCommon\Mapper\AbstractNestedSet'                  => __DIR__ . '/src/UthandoCommon/Mapper/AbstractNestedSet.php',
  'UthandoCommon\Mapper\DbAdapterAwareInterface'            => __DIR__ . '/src/UthandoCommon/Mapper/DbAdapterAwareInterface.php',
  'UthandoCommon\Mapper\MapperException'                    => __DIR__ . '/src/UthandoCommon/Mapper/MapperException.php',
  
  'UthandoCommon\Model\AbstractCollection'                  => __DIR__ . '/src/UthandoCommon/Model/AbstractCollection.php',
  'UthandoCommon\Model\CollectionException'                 => __DIR__ . '/src/UthandoCommon/Model/CollectionException.php',
  'UthandoCommon\Model\Model'                               => __DIR__ . '/src/UthandoCommon/Model/Model.php',
  'UthandoCommon\Model\ModelInterface'                      => __DIR__ . '/src/UthandoCommon/Model/ModelInterface.php',
  
  'UthandoCommon\Service\AbstractService'                   => __DIR__ . '/src/UthandoCommon/Service/AbstractService.php',
  'UthandoCommon\Service\ServiceException'                  => __DIR__ . '/src/UthandoCommon/Service/ServiceException.php',
  'UthandoCommon\Service\Factory\DbAdapterServiceFactory'   => __DIR__ . '/src/UthandoCommon/Service/Factory/DbAdapterServiceFactory.php',
  'UthandoCommon\Service\Initializer\DbAdapterInitializer'  => __DIR__ . '/src/UthandoCommon/Service/Initializer/DbAdapterInitializer.php',
  
  'UthandoCommon\View\AbstractViewHelper'                   => __DIR__ . '/src/UthandoCommon/View/AbstractViewHelper.php',
  'UthandoCommon\View\Alert'                                => __DIR__ . '/src/uthandoCommon/View/Alert.php',
  'UthandoCommon\View\FlashMessenger'                       => __DIR__ . '/src/UthandoCommon/View/FlashMessenger.php',
  'UthandoCommon\View\FormatDate'                           => __DIR__ . '/src/UthandoCommon/View/FormatDate.php',
  'UthandoCommon\View\Request'                              => __DIR__ . '/src/UthandoCommon/View/Request.php',
];
