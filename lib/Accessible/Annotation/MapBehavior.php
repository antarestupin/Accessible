<?php

namespace Accessible\Annotation;

/**
 * A property having this annotation will behave as a map.
 *
 * @Annotation
 * @Target("PROPERTY")
 * @Attributes({
 *   @Attribute("itemName", type="string"),
 *   @Attribute("methods", type="array<string>")
 * })
 */
class MapBehavior extends AbstractCollectionBehavior
{

}
