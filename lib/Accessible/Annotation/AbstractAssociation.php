<?php

namespace Accessible\Annotation;

abstract class AbstractAssociation
{
    /**
     * The name of the property that refers to the current class.
     *
     * @var string
     */
    protected $associatedProperty;

    /**
     * The name of the class of the property.
     *
     * @var string
     */
    protected $className;

    /**
     * Initializes the annotation.
     *
     * @param mixed $value The name of the property that refers to the current class.
     */
    public function __construct($values)
    {
        $this->className = $values['className'];
    }

    /**
     * Get the name of the property that refers to the current class.
     *
     * @return string The value.
     */
    public function getAssociatedProperty()
    {
        return $this->associatedProperty;
    }

    /**
     * Get the name of the class of the property.
     *
     * @return string The value.
     */
    public function getClassName()
    {
        return $this->className;
    }
}
