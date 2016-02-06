<?php

namespace Accessible\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class InitializeObject
{
    /**
     * The name of the class to initialize.
     *
     * @var mixed
     */
    private $className;

    /**
     * Initializes the annotation.
     *
     * @param mixed $className The annotation's parameters.
     */
    public function __construct($className)
    {
        $this->className = $className['value'];
    }

    /**
     * Get the name of the class to initialize.
     *
     * @return mixed The value.
     */
    public function getClassName()
    {
        return $this->className;
    }
}
