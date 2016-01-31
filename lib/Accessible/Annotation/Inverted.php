<?php

namespace Accessible\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 * @Attributes({
 *   @Attribute("className", type="string"),
 *   @Attribute("invertedBy", type="string")
 * })
 */
class Inverted extends AbstractAssociation
{
    /**
     * Initializes the annotation.
     *
     * @param mixed $value The name of the property that refers to the current class.
     */
    public function __construct($values)
    {
        $this->associatedProperty = $values['invertedBy'];
        parent::__construct($values);
    }
}
