<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Model
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Model;

use Zend\Hydrator\HydratorInterface;

/**
 * Interface ModelAwareInterface
 *
 * @package UthandoCommon\Model
 */
interface ModelAwareInterface
{
    /**
     * @param null $data
     * @return ModelInterface
     */
    public function getModel($data = null);

    /**
     * @param ModelInterface $model
     * @return ModelInterface
     */
    public function setModel(ModelInterface $model);

    /**
     * @param $dataOrModel
     * @param HydratorInterface $hydrator
     * @return array
     */
    public function extract($dataOrModel, HydratorInterface $hydrator = null);
}
