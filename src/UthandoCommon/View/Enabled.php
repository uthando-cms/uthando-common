<?php
namespace UthandoCommon\View;

use Zend\View\Helper\AbstractHelper;

class Enabled extends AbstractHelper
{
    public function __invoke($model, $params)
    {
        $id = 'get' . ucfirst($params['table']) . 'Id';
        
    	$url = $this->view->url($params['route'], [
    		'action'   => 'set-enabled',
    		'id'       => $model->$id()
    	]);
    
    	$format = '<p class="'.$params['table'].'-status"><a href="%s" class="glyphicon glyphicon-%s '.$params['table'].'-%s">&nbsp;</a></p>';
    
    	if ($model->isEnabled()) {
    		$icon = 'ok';
    		$class = 'enabled';
    	} else {
    		$icon = 'remove';
    		$class = 'disabled';
    	}
    
    	return sprintf($format, $url, $icon, $class);
    }
}
