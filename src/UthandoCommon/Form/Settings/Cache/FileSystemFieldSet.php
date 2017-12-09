<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 25/09/17 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\Form\Settings\Cache;

use TwbBundle\Form\View\Helper\TwbBundleForm;
use Zend\Cache\Storage\Adapter\FilesystemOptions;
use Zend\Filter\Boolean;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Hydrator\ClassMethods;
use Zend\I18n\Validator\IsInt;
use Zend\Validator\StringLength;

class FileSystemFieldSet extends BaseOptionsFieldSet
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods());
        $this->setObject(new FilesystemOptions());
    }

    public function init()
    {
        parent::init();

        $this->add([
            'type' => Text::class,
            'name' => 'namespace_separator',
            'options' => [
                'label' => 'Namespace Separator',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'help-block' => 'A separator for the namespace and prefix.',
            ],
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'cache_dir',
            'options' => [
                'label' => 'Cache Directory',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'help-block' => 'Directory to store cache files.',
            ],
        ]);

        $this->add([
            'type' => Checkbox::class,
            'name' => 'clear_stat_cache',
            'options' => [
                'label' => 'Clear Stat Cache',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
                'required' 		=> false,
                'column-size' => 'md-8 col-md-offset-4',
                'help-block' => 'Call clearstatcache() enabled?'
            ],
        ]);

        $this->add([
            'type' => Number::class,
            'name' => 'dir_level',
            'options' => [
                'label' => 'Directory Level',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'help-block' => 'Defines how much sub-directories should be created.',
            ],
        ]);

        $this->add([
            'type' => Select::class,
            'name' => 'dir_permission',
            'options' => [
                'label' => 'Directory Permission',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'help-block' => 'Set explicit permission on creating new directories.',
                'value_options' => [
                    0700 => '700 -rwx------',
                    0701 => '701 -rwx-----x',
                    0703 => '703 -rwx----wx',
                    0705 => '705 -rwxr----x',
                    0707 => '707 -rwx---rwx',
                    0710 => '710 -rwx--x---',
                    0711 => '711 -rwx--x--x',
                    0730 => '730 -rwx-wx---',
                    0733 => '733 -rwx-wx-wx',
                    0750 => '750 -rwxr-x---',
                    0755 => '755 -rwxr-xr-x',
                    0770 => '770 -rwxrwx---',
                    0777 => '777 -rwxrwxrwx',
                ],
            ],
        ]);

        $this->add([
            'type' => Checkbox::class,
            'name' => 'file_locking',
            'options' => [
                'label' => 'File Locking',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
                'required' 		=> false,
                'column-size' => 'md-8 col-md-offset-4',
                'help-block' => 'Lock files on writing.',
            ],
        ]);

        $this->add([
            'type' => Select::class,
            'name' => 'file_permission',
            'options' => [
                'label' => 'File Permission',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'help-block' => 'Set explicit permission on creating new files.',
                'value_options' => [
                    0600 => '600 -rw-------',
                    0602 => '622 -rw-----w-',
                    0604 => '644 -rw----r--',
                    0606 => '666 -rw----rw-',
                    0620 => '620 -rw--w----',
                    0622 => '622 -rw--w--w-',
                    0640 => '640 -rw-r-----',
                    0644 => '644 -rw-r--r--',
                    0660 => '660 -rw-rw----',
                    0666 => '666 -rw-rw-rw-',
                ],
            ],
        ]);

        $this->add([
            'type' => Checkbox::class,
            'name' => 'no_atime',
            'options' => [
                'label' => 'No atime',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
                'required' 		=> false,
                'column-size' => 'md-8 col-md-offset-4',
                'help-block' => 'Don’t get ‘fileatime’ as ‘atime’ on metadata.',
            ],
        ]);

        $this->add([
            'type' => Checkbox::class,
            'name' => 'no_ctime',
            'options' => [
                'label' => 'No ctime',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
                'required' 		=> false,
                'column-size' => 'md-8 col-md-offset-4',
                'help-block' => 'Don’t get ‘filectime’ as ‘ctime’ on metadata.',
            ],
        ]);

        $this->add([
            'type' => Checkbox::class,
            'name' => 'umask',
            'options' => [
                'label' => 'umask',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
                'required' 		=> false,
                'column-size' => 'md-8 col-md-offset-4',
                'help-block' => 'Use <a href="http://wikipedia.org/wiki/Umask" target="_blank">umask</a> to set file and directory permissions.'
            ],
        ]);

        // load in default values
        $defaultOptions = $this->getObject()->toArray();

        foreach ($defaultOptions as $key => $value) {
            if ($this->has($key)) {
                $this->get($key)->setValue($value);
            }
        }
    }

    public function getInputFilterSpecification()
    {
        $filters = parent::getInputFilterSpecification();

        $fileSystemFilters = [
            'namespace_separator' => [
                'required' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                ],
                'validators' => [
                    ['name' => StringLength::class, 'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 1,
                    ]],
                ],
            ],
            'cache_dir' => [
                'required' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                ],
                'validators' => [
                    ['name' => StringLength::class, 'options' => [
                        'encoding' => 'UTF-8',
                    ]],
                ],
            ],
            'clear_stat_cache' => [
                'required' => false,
                'allow_empty' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                    ['name' => Boolean::class, 'options' => [
                        'type' => Boolean::TYPE_ZERO_STRING,
                    ]],
                ],
            ],
            'dir_level' => [
                'required' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                    ['name' => ToInt::class],
                ],
                'validators' => [
                    ['name' => IsInt::class],
                ],
            ],
            'dir_permission' => [
                'required' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                    ['name' => ToInt::class],
                ],
                'validators' => [
                    /*['name' => StringLength::class, 'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 3,
                        'max'      => 3,
                    ]],*/
                ],
            ],
            'file_locking' => [
                'required' => false,
                'allow_empty' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                    ['name' => Boolean::class, 'options' => [
                        'type' => Boolean::TYPE_ZERO_STRING,
                    ]],
                ],
            ],
            'file_permission' => [
                'required' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                    ['name' => ToInt::class],
                ],
                /*'validators' => [
                    ['name' => StringLength::class, 'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 3,
                        'max'      => 3,
                    ]],
                ],*/
            ],
            'no_atime' => [
                'required' => false,
                'allow_empty' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                    ['name' => Boolean::class, 'options' => [
                        'type' => Boolean::TYPE_ZERO_STRING,
                    ]],
                ],
            ],
            'no_ctime' => [
                'required' => false,
                'allow_empty' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                    ['name' => Boolean::class, 'options' => [
                        'type' => Boolean::TYPE_ZERO_STRING,
                    ]],
                ],
            ],
            'umask' => [
                'required' => false,
                'allow_empty' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class,],
                    ['name' => Boolean::class, 'options' => [
                        'type' => Boolean::TYPE_ZERO_STRING,
                    ]],
                ],
            ],
        ];

        $filters = array_merge($filters, $fileSystemFilters);

        return $filters;
    }
}
