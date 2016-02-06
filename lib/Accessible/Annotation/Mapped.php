<?php

namespace Accessible\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 * @Attributes({
 *   @Attribute("className", type="string"),
 *   @Attribute("mappedBy", type="string")
 * })
 */
class Mapped extends AbstractAssociation
{
    /**
     * Initializes the annotation.
     *
     * @param mixed $values The annotation's parameters.
     */
    public function __construct($values)
    {
        $this->associatedProperty = $values['mappedBy'];
        parent::__construct($values);
    }
}
