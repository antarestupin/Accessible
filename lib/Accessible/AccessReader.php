<?php

namespace Accessible;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use \Accessible\Annotations\Access;

class AccessReader
{
    /**
     * The annotations reader used to know the access.
     *
     * @var Doctrine\Common\Annotations\Reader
     */
    private static $reader = null;

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
     * The constraints validator.
     *
     * @var Symfony\Component\Validator\ConstraintValidator
     */
    private static $constraintsValidator;

    /**
     * Get the annotation reader that is used.
     * Initializes it if it doesn't already exists.
     *
     * @return Reader The annotation reader.
     */
    public static function getAnnotationReader()
    {
        if (self::$reader === null) {
            self::$reader = new CachedReader(new AnnotationReader(), new ArrayCache());
        }

        return self::$reader;
    }

    /**
     * Set the annotation reader that will be used.
     *
     * @param Reader $reader The annotation reader.
     */
    public static function setAnnotationReader(Reader $reader)
    {
        self::$reader = $reader;
    }

    /**
     * Get the constraints validator that is used.
     * Initializes it if it doesn't already exists.
     *
     * @return ConstraintValidator The annotation reader.
     */
    public static function getConstraintsValidator()
    {
        if (self::$constraintsValidator === null) {
            self::$constraintsValidator = Validation::createValidatorBuilder()
                ->enableAnnotationMapping(self::getAnnotationReader())
                ->getValidator();
        }

        return self::$constraintsValidator;
    }

    /**
     * Set the constraints validator that will be used.
     *
     * @param ConstraintValidator $constraintsValidator The annotation reader.
     */
    public static function setConstraintsValidator(ValidatorInterface $constraintsValidator)
    {
        self::$constraintsValidator = $constraintsValidator;
    }

    /**
     * Get a list of classes and traits to analyze.
     *
     * @param \ReflectionObject $reflectionObject The object to get the parents from.
     *
     * @return array The list of classes to read.
     */
    private static function getClassesToRead(\ReflectionObject $reflectionObject)
    {
        $objectClasses = array($reflectionObject);
        $objectTraits = $reflectionObject->getTraits();
        if (!empty($objectTraits)) {
            foreach ($objectTraits as $trait) {
                $objectClasses[] = $trait;
            }
        }

        $parentClass = $reflectionObject->getParentClass();
        while($parentClass) {
            $objectClasses[] = $parentClass;

            $parentTraits = $parentClass->getTraits();
            if (!empty($parentTraits)) {
                foreach ($parentTraits as $trait) {
                    $objectClasses[] = $trait;
                }
            }

            $parentClass = $parentClass->getParentClass();
        }

        array_reverse($objectClasses);

        return $objectClasses;
    }

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

        $annotationReader = self::getAnnotationReader();
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

        $annotation = self::getAnnotationReader()->getClassAnnotation($reflectionObject, self::$disableConstraintsValidationAnnotationClass);

        return $annotation === null;
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
        return self::getConstraintsValidator()->validatePropertyValue($object, $property, $value);
    }
}
