<?php

namespace Accessible\Reader;

use \Accessible\Configuration;
use \Accessible\Annotations\Access;

class AccessReader extends Reader
{
    /**
     * The name of the annotation class that define the properties access.
     *
     * @var string
     */
    private static $accessAnnotationClass = "Accessible\\Annotations\\Access";

    /**
     * The name of the annotation class that enable the constraints validation for a class.
     *
     * @var string
     */
    private static $enableConstraintsValidationAnnotationClass = "Accessible\\Annotations\\EnableConstraintsValidation";

    /**
     * The name of the annotation class that disable the constraints validation for a class.
     *
     * @var string
     */
    private static $disableConstraintsValidationAnnotationClass = "Accessible\\Annotations\\DisableConstraintsValidation";

    /**
     * Get a list of properties and the access that are given to them for given object.
     *
     * @param object $object The object to read.
     *
     * @return array The list of properties and their access.
     */
    public static function getAccessProperties($object)
    {
        $objectAccessProperties = array();

        $reflectionObject = new \ReflectionObject($object);

        $objectClasses = self::getClassesToRead($reflectionObject);
        array_reverse($objectClasses);

        $annotationReader = Configuration::getAnnotationReader();
        foreach($objectClasses as $class) {
            foreach ($class->getProperties() as $property) {
                $annotation = $annotationReader->getPropertyAnnotation($property, self::$accessAnnotationClass);
                $propertyName = $property->getName();

                if (empty($objectAccessProperties[$propertyName])) {
                    $objectAccessProperties[$propertyName] = array();
                }

                if ($annotation !== null) {
                    $accessProperties = $annotation->getAccessProperties();
                    $objectAccessProperties[$propertyName] = $accessProperties;
                }
            }
        }

        return $objectAccessProperties;
    }

    /**
     * Indicates wether the constraints validation is enabled or not for the given object.
     *
     * @param object  $object The object to read.
     *
     * @return boolean True if the validation is enabled, else false.
     */
    public static function isConstraintsValidationEnabled($object)
    {
        $reflectionObject = new \ReflectionObject($object);
        $objectClasses = self::getClassesToRead($reflectionObject);
        $reader = Configuration::getAnnotationReader();

        foreach ($objectClasses as $class) {
            if ($reader->getClassAnnotation($class, self::$disableConstraintsValidationAnnotationClass) !== null) {
                return false;
            }
            if ($reader->getClassAnnotation($class, self::$enableConstraintsValidationAnnotationClass) !== null) {
                return true;
            }
        }

        return true;
    }

    /**
     * Validates the given value compared to given property constraints.
     * If the value is valid, a call to `count` to the object returned
     * by this method should give 0.
     *
     * @param object $object   The object to compare.
     * @param string $property The name of the reference property.
     * @param mixed  $value    The value to check.
     *
     * @return Symfony\Component\Validator\ConstraintViolationList
     *         The list of constraints violations the check returns.
     */
    public static function validatePropertyValue($object, $property, $value)
    {
        return Configuration::getConstraintsValidator()->validatePropertyValue($object, $property, $value);
    }
}
