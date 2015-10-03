<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Controller
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Controller;

use Zend\Http\PHPEnvironment\Response;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class CaptchaController
 *
 * @package UthandoCommon\Controller
 * @method Response getResponse()
 */
class CaptchaController extends AbstractActionController
{
    /**
     * @return Response
     */
    public function generateAction()
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

                $imageRead = file_get_contents($image);

                $response->setStatusCode(200);
                $response->setContent($imageRead);

                if (file_exists($image) == true) {
                    unlink($image);
                }
            }

        }

        return $response;
    }
}
