<?php

namespace Accessible\Reader;

use \Accessible\Annotations\Construct;

class AutoConstructReader extends Reader
{
    /**
     * The name of the annotation class that define the construct arguments.
     *
     * @var string
     */
    private static $constructAnnotationClass = "Accessible\\Annotations\\Construct";

    /**
     * Get the list of needed arguments for given object's constructor.
     *
     * @param object $object The object to analyze.
     *
     * @return array The list of arguments.
     */
    public static function getConstructArguments($object)
    {
        $reflectionObject = new \ReflectionObject($object);
    }
}
