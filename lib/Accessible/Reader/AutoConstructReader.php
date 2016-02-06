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
     * @param object $object The object to analyze.
     *
     * @return array The list of arguments.
     */
    public static function getConstructArguments($object)
    {
        $reflectionObject = new \ReflectionObject($object);
        $cacheId = md5("constructArguments:" . $reflectionObject->getName());
        $constructArguments = self::getFromCache($cacheId);
        if ($constructArguments !== null) {
            return $constructArguments;
        }

        $constructArguments = null;
        $annotationReader = Configuration::getAnnotationReader();
        $objectClasses = self::getClassesToRead($reflectionObject);
        array_reverse($objectClasses);

        foreach ($objectClasses as $class) {
            $annotation = $annotationReader->getClassAnnotation($class, self::$constructAnnotationClass);
            if ($annotation !== null) {
                $constructArguments = $annotation->getArguments();
                break;
            }
        }

        self::saveToCache($cacheId, $constructArguments);

        return $constructArguments;
    }

    /**
     * Get the list of properties that have to be initialized automatically
     * during the object construction, plus their value.
     *
     * @param object $object The object to analyze.
     *
     * @return array The list of properties and values,
     *               in the form ["property" => "value"].
     */
    public static function getPropertiesToInitialize($object)
    {
        $reflectionObject = new \ReflectionObject($object);
        $cacheId = md5("propertiesToInitialize:" . $reflectionObject->getName());
        $propertiesValues = self::getFromCache($cacheId);
        if ($propertiesValues !== null) {
            return $propertiesValues;
        }

        $annotationReader = Configuration::getAnnotationReader();
        $objectClasses = self::getClassesToRead($reflectionObject);
        array_reverse($objectClasses);

        $propertiesValues = array();

        foreach ($objectClasses as $class) {
            foreach ($class->getProperties() as $property) {
                $propertyName = $property->getName();

                $annotation = $annotationReader->getPropertyAnnotation($property, self::$initializeAnnotationClass);
                $annotationType = "initialize";
                if ($annotation === null) {
                    $annotation = $annotationReader->getPropertyAnnotation($property, self::$initializeObjectAnnotationClass);
                    $annotationType = "initializeObject";
                }

                if (empty($propertiesValues[$propertyName]) && $annotation !== null) {
                    switch ($annotationType) {
                        case "initialize":
                            $propertiesValues[$propertyName] = $annotation->getValue();
                            break;
                        case "initializeAnnotation":
                            $className = $annotation->getClassName();
                            $propertiesValues[$propertyName] = new $className();
                            break;
                    }
                }
            }
        }

        self::saveToCache($cacheId, $propertiesValues);

        return $propertiesValues;
    }
}
