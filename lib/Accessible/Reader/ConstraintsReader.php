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
     * @param array  $objectClasses The classes of the object to read.
     * @param \Doctrine\Common\Annotations\Reader $annotationReader The annotation reader to use.
     *
     * @return boolean True if the validation is enabled, else false.
     */
    public static function isConstraintsValidationEnabled($objectClasses, $annotationReader)
    {
        $enabled = true;

        foreach ($objectClasses as $class) {
            if ($annotationReader->getClassAnnotation($class, self::$disableConstraintsValidationAnnotationClass) !== null) {
                $enabled = false;
                break;
            }
            if ($annotationReader->getClassAnnotation($class, self::$enableConstraintsValidationAnnotationClass) !== null) {
                $enabled = true;
                break;
            }
        }

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
