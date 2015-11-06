<?php

namespace Accessible\Annotations;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Initialize
{
    /**
     * The value the property should be initialized to.
     *
     * @var mixed
     */
    private $value;

    /**
     * Initializes the annotation.
     *
     * @param mixed $value The value to give to the property.
     */
    public function __construct($value)
    {
        $this->value = $value['value'];
    }

    /**
     * Get the value the property should be initialized to.
     *
     * @return mixed The value.
     */
    public function getValue()
    {
        return $this->value;
    }
}
