<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Form\View\Helper
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2017 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Form\View\Helper;

use Zend\Form\View\Helper\FormSelect as ZendFormSelect;
use Zend\Stdlib\ArrayUtils;

class FormSelect extends ZendFormSelect
{
    /**
     * Attributes valid for options
     *
     * @var array
     */
    protected $validOptionAttributes = [
        'disabled' => true,
        'selected' => true,
        'label'    => true,
        'value'    => true,
        'disable_html_escape' => true,
    ];

    /**
     * Render an array of options
     *
     * Individual options should be of the form:
     *
     * <code>
     * array(
     *     'value'    => 'value',
     *     'label'    => 'label',
     *     'disabled' => $booleanFlag,
     *     'selected' => $booleanFlag,
     * )
     * </code>
     *
     * @param  array $options
     * @param  array $selectedOptions Option values that should be marked as selected
     * @return string
     */
    public function renderOptions(array $options, array $selectedOptions = [])
    {
        $template      = '<option %s>%s</option>';
        $optionStrings = [];
        $escapeHtml    = $this->getEscapeHtmlHelper();

        foreach ($options as $key => $optionSpec) {
            $value      = '';
            $label      = '';
            $selected   = false;
            $disabled   = false;
            $escape     = false;

            if (is_scalar($optionSpec)) {
                $optionSpec = [
                    'label' => $optionSpec,
                    'value' => $key
                ];
            }

            if (isset($optionSpec['options']) && is_array($optionSpec['options'])) {
                $optionStrings[] = $this->renderOptgroup($optionSpec, $selectedOptions);
                continue;
            }

            if (isset($optionSpec['value'])) {
                $value = $optionSpec['value'];
            }
            if (isset($optionSpec['label'])) {
                $label = $optionSpec['label'];
            }
            if (isset($optionSpec['selected'])) {
                $selected = $optionSpec['selected'];
            }
            if (isset($optionSpec['disabled'])) {
                $disabled = $optionSpec['disabled'];
            }
            if (isset($optionSpec['disable_html_escape'])) {
                $escape = $optionSpec['disable_html_escape'];
            }

            if (ArrayUtils::inArray($value, $selectedOptions)) {
                $selected = true;
            }

            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate(
                    $label,
                    $this->getTranslatorTextDomain()
                );
            }

            $attributes = compact('value', 'selected', 'disabled');

            if (isset($optionSpec['attributes']) && is_array($optionSpec['attributes'])) {
                $attributes = array_merge($attributes, $optionSpec['attributes']);
            }

            if (! $escape) {
                $label = $escapeHtml($label);
            }

            $this->validTagAttributes = $this->validOptionAttributes;
            $optionStrings[] = sprintf(
                $template,
                $this->createAttributesString($attributes),
                $label
            );
        }

        return implode("\n", $optionStrings);
    }
}
