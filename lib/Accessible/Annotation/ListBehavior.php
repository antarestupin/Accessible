<?php

namespace Accessible\Annotation;

/**
 * A property having this annotation will behave as a list.
 *
 * @Annotation
 * @Target("PROPERTY")
 * @Attributes({
 *   @Attribute("itemName", type="string"),
 *   @Attribute("methods", type="array<string>")
 * })
 */
class ListBehavior extends AbstractCollectionBehavior
{
    /**
     * Initializes the annotation.
     *
     * @param mixed $values The annotation's parameters.
     */
    public function __construct($values)
    {
        $this->defaultMethods = array(AbstractCollectionBehavior::ADD, AbstractCollectionBehavior::REMOVE);
        parent::__construct($values);
    }
}