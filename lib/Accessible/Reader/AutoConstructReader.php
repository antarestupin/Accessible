<?php

namespace Accessible\Reader;

use \Accessible\Configuration;

class AutoConstructReader extends Reader
{
    /**
     * The name of the annotation class that define the construct arguments.
     *
     * @var string
     */
    private static $constructAnnotationClass = "Accessible\\Annotation\\Construct";

    /**
     * The name of the annotation class that define a property's default value.
     *
     * @var string
     */
    private static $initializeAnnotationClass = "Accessible\\Annotation\\Initialize";

    /**
     * The name of the annotation class that define the initial value of an object property.
     *
     * @var string
     */
    private static $initializeObjectAnnotationClass = "Accessible\\Annotation\\InitializeObject";

    /**
     * Get the list of needed arguments for given object's constructor.
     *
     * @param array  $objectClasses The classes of the object to read.
     * @param Reader $objectClasses The annotation reader to use.
     *
     * @return array The list of arguments.
     */
    public static function getConstructArguments($objectClasses, $annotationReader)
    {
        $constructArguments = null;
        array_reverse($objectClasses);

        foreach ($objectClasses as $class) {
            $annotation = $annotationReader->getClassAnnotation($class, self::$constructAnnotationClass);
            if ($annotation !== null) {
                $constructArguments = $annotation->getArguments();
                break;
            }
        }

        return $constructArguments;
    }

    /**
     * Get the list of properties that have to be initialized automatically
     * during the object construction, plus their value.
     *
     * @param array  $properties The properties of the object to read.
     * @param Reader $objectClasses The annotation reader to use.
     *
     * @return array The list of properties and values,
     *               in the form ["property" => "value"].
     */
    public static function getPropertiesToInitialize($properties, $annotationReader)
    {
        $propertiesValues = array();

        foreach ($properties as $propertyName => $property) {
            $initializeAnnotation = $annotationReader->getPropertyAnnotation($property, self::$initializeAnnotationClass);
            $initializeObjectAnnotation = $annotationReader->getPropertyAnnotation($property, self::$initializeObjectAnnotationClass);

            if ($initializeAnnotation !== null && $initializeObjectAnnotation !== null) {
                throw new \LogicException("Two initial values are given for property $propertyName.");
            }

            if (empty($propertiesValues[$propertyName])) {
                if ($initializeAnnotation !== null) {
                    $propertiesValues[$propertyName] = $initializeAnnotation->getValue();
                } else if ($initializeObjectAnnotation !== null) {
                    $className = $initializeObjectAnnotation->getClassName();
                    $propertiesValues[$propertyName] = new $className();
                }
            }
        }

        return $propertiesValues;
    }
}
