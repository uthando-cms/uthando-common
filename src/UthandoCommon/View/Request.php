<?php

namespace UthandoCommon\View;

use UthandoCommon\View\AbstractViewHelper;
 
class Request extends AbstractViewHelper
{
    protected $serviceLocator;
 
    public function __invoke()
    {
        return $this->getServiceLocator()
			->getServiceLocator()
			->get('Request');
    }
}