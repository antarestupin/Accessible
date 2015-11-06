<?php

namespace Accessible;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Configuration
{
    /**
     * The annotations reader used to know the access.
     *
     * @var Doctrine\Common\Annotations\Reader
     */
    private static $annotationReader;

    /**
     * The constraints validator.
     *
     * @var Symfony\Component\Validator\ConstraintValidator
     */
    private static $constraintsValidator;

    /**
     * Says if the @Initialize and @InitializeObject values have to
     * be validated with constraints.
     *
     * @var bool
     */
    private static $initializeValuesValidationEnabled = true;

    /**
     * Get the annotation reader that is used.
     * Initializes it if it doesn't already exists.
     *
     * @return Reader The annotation reader.
     */
    public static function getAnnotationReader()
    {
        if (self::$annotationReader === null) {
            self::$annotationReader = new CachedReader(new AnnotationReader(), new ArrayCache());
        }

        return self::$annotationReader;
    }

    /**
     * Set the annotation reader that will be used.
     *
     * @param Reader $annotationReader The annotation reader.
     */
    public static function setAnnotationReader(Reader $annotationReader)
    {
        self::$annotationReader = $annotationReader;
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

    public static function isInitializeValuesValidationEnabled()
    {
        return self::$initializeValuesValidationEnabled;
    }

    /**
     * Enable or disable the constraints validation for @Initialize and
     * @InitializeObject values.
     *
     * @param bool $enabled True for enable, false for disable.
     */
    public static function setInitializeValuesValidationEnabled($enabled)
    {
        if (!in_array($enabled, array(true, false))) {
            throw new \InvalidArgumentException("This value must be a boolean.");
        }

        self::$initializeValuesValidationEnabled = $enabled;
    }
}
