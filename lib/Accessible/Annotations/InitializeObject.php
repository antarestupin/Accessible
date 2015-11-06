<?php

namespace Accessible\Annotations;

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
     * @param mixed $className The name of the class.
     */
    public function __construct($className)
    {
        $this->value = $className['value'];
    }

    /**
     * Get the name of the class to initialize.
     *
     * @return mixed The value.
     */
    public function getClassName()
    {
        return $this->value;
    }
}
