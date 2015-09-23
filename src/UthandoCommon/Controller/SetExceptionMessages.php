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

use Exception;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

/**
 * Class SetExceptionMessages
 *
 * @package UthandoCommon\Controller
 * @method FlashMessenger flashMessenger()
 */
trait SetExceptionMessages
{
    /**
     * Sets a exception message for flash plugin.
     *
     * @param Exception $e
     */
    public function setExceptionMessages(Exception $e)
    {
        $this->flashMessenger()->addErrorMessage([
            'message' => $e->getMessage(),
            'title' => 'Error!'
        ]);

        $prevException = $e->getPrevious();

        if ($prevException) {
            while ($prevException) {
                $this->flashMessenger()->addErrorMessage($prevException->getMessage());
                $prevException = $prevException->getPrevious();
            }
        }
    }
}
