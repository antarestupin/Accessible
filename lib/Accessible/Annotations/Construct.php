<?php

namespace Accessible\Annotations;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Construct
{
    /**
     * The construct arguments.
     *
     * @var array
     */
    private $arguments;

    /**
     * Initializes the annotation.
     * @param array $arguments The construct arguments.
     */
    public function __construct(array $arguments)
    {
        $this->arguments = $arguments['value'];
    }

    /**
     * Get the list of the construct arguments.
     *
     * @return array The list of arguments.
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}
