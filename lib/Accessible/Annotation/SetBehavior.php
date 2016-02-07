<?php

namespace Accessible\Annotation;

/**
 * A property having this annotation will behave as a set.
 *
 * @Annotation
 * @Target("PROPERTY")
 * @Attributes({
 *   @Attribute("itemName", type="string"),
 *   @Attribute("methods", type="array<string>")
 * })
 */
class SetBehavior extends AbstractCollectionBehavior
{

}
