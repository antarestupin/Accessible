<?php

namespace Accessible\Reader;

use \Accessible\Configuration;

class ConstraintsReader extends Reader
{
    /**
     * The name of the annotation class that enable the constraints validation for a class.
     *
     * @var string
     */
    private static $enableConstraintsValidationAnnotationClass = "Accessible\\Annotation\\EnableConstraintsValidation";

    /**
     * The name of the annotation class that disable the constraints validation for a class.
     *
     * @var string
     */
    private static $disableConstraintsValidationAnnotationClass = "Accessible\\Annotation\\DisableConstraintsValidation";

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
        $cacheDriver = Configuration::getCacheDriver();
        $cacheId = "isConstraintsValidationEnabled:" . $reflectionObject->getName();
        if ($cacheDriver->contains($cacheId)) {
            $cacheDriver->fetch($cacheId);
        }

        $objectClasses = self::getClassesToRead($reflectionObject);
        $reader = Configuration::getAnnotationReader();
        $enabled = true;

        foreach ($objectClasses as $class) {
            if ($reader->getClassAnnotation($class, self::$disableConstraintsValidationAnnotationClass) !== null) {
                $enabled = false;
                break;
            }
            if ($reader->getClassAnnotation($class, self::$enableConstraintsValidationAnnotationClass) !== null) {
                $enabled = true;
                break;
            }
        }

        $cacheDriver->save($cacheId, $enabled);

        return $enabled;
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
