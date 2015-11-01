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
    private static $annotationClass = "Accessible\\Annotations\\Access";

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

        foreach ($reflectionObject->getProperties() as $property) {
            $annotation = self::getAnnotationReader()->getPropertyAnnotation($property, self::$annotationClass);
            $propertyName = $property->getName();

            $objectAccessProperties[$propertyName] = array();
            if ($annotation !== null) {
                $accessProperties = $annotation->getAccessProperties();
                $objectAccessProperties[$propertyName] = $accessProperties;
            }
        }

        return $objectAccessProperties;
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
