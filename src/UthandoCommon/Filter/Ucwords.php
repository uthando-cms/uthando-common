<?php
namespace UthandoCommon\Filter;

use Zend\Filter\AbstractUnicode;
use Traversable;

class Ucwords extends AbstractUnicode
{
    /**
     * @var array
     */
    protected $options = array(
        'encoding' => null,
    );
    
    /**
     * Constructor
     *
     * @param string|array|Traversable $encodingOrOptions OPTIONAL
     */
    public function __construct($encodingOrOptions = null)
    {
        if ($encodingOrOptions !== null) {
            if (!static::isOptions($encodingOrOptions)) {
                $this->setEncoding($encodingOrOptions);
            } else {
                $this->setOptions($encodingOrOptions);
            }
        }
    }
    
    /**
     * Defined by Zend\Filter\FilterInterface
     *
     * Returns the string $value, converting words to have an uppercase first character as necessary
     *
     * If the value provided is non-scalar, the value will remain unfiltered
     *
     * @param  string $value
     * @return string|mixed
     */
    public function filter($value)
    {
        if (!is_scalar($value)) {
            return $value;
        }
        
        $value = (string) $value;

        if ($this->options['encoding'] !== null) {
            return mb_convert_case($value, MB_CASE_TITLE,  $this->options['encoding']);
        }

        return ucwords(strtolower($value));
    }
}
