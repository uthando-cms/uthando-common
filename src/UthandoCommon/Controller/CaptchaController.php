<?php
namespace UthandoCommon\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class CaptchaController extends AbstractActionController
{
    public function generateAction ()
    {
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', "image/png");
    
        $id = $this->params('id', false);
    
        if ($id) {
            $config = $this->getServiceLocator()->get('config');
    
            $spec = $config['uthando_common']['captcha'];
    
            $image = join('/', [
                $spec['options']['imgDir'],
                $id
            ]);
    
            if (file_exists($image) !== false) {
    
                $imageread = file_get_contents($image);
    
                $response->setStatusCode(200);
                $response->setContent($imageread);
                 
                if (file_exists($image) == true) {
                    unlink($image);
                }
            }
    
        }
    
        return $response;
    }
}
