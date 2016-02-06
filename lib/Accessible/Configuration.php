<?php

namespace Accessible;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\Cache;
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
     * Says if the Initialize and InitializeObject values have to
     * be validated with constraints.
     *
     * @var bool
     */
    private static $initializeValuesValidationEnabled = true;

    /**
     * The cache driver the library will use.
     *
     * @var Cache
     */
    private static $cacheDriver;

    /**
     * The namespace of the cache driver.
     *
     * @var string
     */
    private static $cacheDefaultNamespace = 'antares_accessible_';

    /**
     * The in-memory cache that will be used.
     * @var ArrayCache
     */
    private static $arrayCache;

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
     * @param ValidatorInterface $constraintsValidator The annotation reader.
     */
    public static function setConstraintsValidator(ValidatorInterface $constraintsValidator)
    {
        self::$constraintsValidator = $constraintsValidator;
    }

    /**
     * Indicates if the constraints validation for Initialize and
     * InitializeObject values is enabled or not.
     *
     * @return boolean True if enabled, else false.
     */
    public static function isInitializeValuesValidationEnabled()
    {
        return self::$initializeValuesValidationEnabled;
    }

    /**
     * Enable or disable the constraints validation for Initialize and
     * InitializeObject values.
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

    /**
     * Get the cache driver that will be used.
     *
     * @return Cache The cache driver.
     */
    public static function getCacheDriver()
    {
        return self::$cacheDriver;
    }

    /**
     * Set the cache driver that will be used.
     *
     * @param Cache  $cache The cache driver.
     * @param string $namespace The cache namespace.
     */
    public static function setCacheDriver(Cache $cache, $namespace = null)
    {
        if ($namespace === null) {
            $namespace = self::$cacheDefaultNamespace;
        }

        if (!is_string($namespace)) {
            throw new \InvalidArgumentException("The namespace must be a string.");
        }

        self::$cacheDriver = $cache;
        self::$cacheDriver->setNamespace($namespace);
    }

    /**
     * Get the array cache that will be used.
     * Initialize it if it doesn't already exist.
     *
     * @return Cache The cache driver.
     */
    public static function getArrayCache()
    {
        if (self::$arrayCache === null) {
            self::setArrayCache(new ArrayCache());
        }

        return self::$arrayCache;
    }

    /**
     * Set the array cache that will be used.
     *
     * @param Cache  $cache The cache driver.
     * @param string $namespace The cache namespace.
     */
    public static function setArrayCache(Cache $cache, $namespace = null) {
        if ($namespace === null) {
            $namespace = self::$cacheDefaultNamespace;
        }

        if (!is_string($namespace)) {
            throw new \InvalidArgumentException("The namespace must be a string.");
        }

        self::$arrayCache = $cache;
        self::$arrayCache->setNamespace($namespace);
    }
}
