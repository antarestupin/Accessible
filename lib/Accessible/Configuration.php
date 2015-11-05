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
    private static $annotationReader = null;

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
}
