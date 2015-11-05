<?php

namespace Accessible\Reader;

use \Accessible\Configuration;
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
        $objectClasses = self::getClassesToRead($reflectionObject);
        $annotationReader = Configuration::getAnnotationReader();

        foreach ($objectClasses as $class) {
            $annotation = $annotationReader->getClassAnnotation($class, self::$constructAnnotationClass);
            if ($annotation !== null) {
                return $annotation->getArguments();
            }
        }

        return null;
    }
}
