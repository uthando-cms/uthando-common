<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 21/09/17 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\InputFilter;

use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Db\NoRecordExists;

trait NoRecordExistsTrait
{
    public function noRecordExists($name, $table, $field, $exclude)
    {
        $exclude = (!$exclude) ?: [
            'field' => $field,
            'value' => $exclude,
        ];

        $this->get($name)
            ->getValidatorChain()
            ->attachByName(NoRecordExists::class, [
                'table' => $table,
                'field' => $field,
                'adapter' => $this->getServiceLocator()
                    ->getServiceLocator()
                    ->get(DbAdapter::class),
                'exclude' => $exclude,
            ]);

        return $this;
    }
}
