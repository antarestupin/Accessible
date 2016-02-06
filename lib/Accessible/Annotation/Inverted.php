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
     * @param mixed $values The annotation's parameters.
     */
    public function __construct($values)
    {
        $this->associatedProperty = $values['invertedBy'];
        parent::__construct($values);
    }
}
