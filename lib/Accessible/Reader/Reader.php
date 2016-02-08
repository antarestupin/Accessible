<?php

namespace Accessible\Reader;

use \Accessible\Configuration;

class Reader
{
    /**
     * The name of the annotation classes that define a collection behavior.
     *
     * @var array<string>
     */
    protected static $collectionAnnotationClasses = array(
        "list" => "Accessible\\Annotation\\ListBehavior",
        "map" => "Accessible\\Annotation\\MapBehavior",
        "set" => "Accessible\\Annotation\\SetBehavior",
    );

    /**
     * Get a list of classes and traits to analyze.
     *
     * @param \ReflectionObject $reflectionObject The object to get the parents from.
     *
     * @return array The list of classes to read.
     */
    public static function getClassesToRead(\ReflectionObject $reflectionObject)
    {
        $cacheId = md5("classesToRead:" . $reflectionObject->getName());
        $objectClasses = self::getFromCache($cacheId);
        if ($objectClasses !== null) {
            return $objectClasses;
        }

        $objectClasses = array($reflectionObject);
        $objectTraits = $reflectionObject->getTraits();
        if (!empty($objectTraits)) {
            foreach ($objectTraits as $trait) {
                $objectClasses[] = $trait;
            }
        }

        $parentClass = $reflectionObject->getParentClass();
        while ($parentClass) {
            $objectClasses[] = $parentClass;

            $parentTraits = $parentClass->getTraits();
            if (!empty($parentTraits)) {
                foreach ($parentTraits as $trait) {
                    $objectClasses[] = $trait;
                }
            }

            $parentClass = $parentClass->getParentClass();
        }

        self::saveToCache($cacheId, $objectClasses);

        return $objectClasses;
    }

    /**
     * Get the properties from a list of classes.
     *
     * @param array $classes
     *
     * @return array
     */
    public static function getProperties($classes)
    {
        array_reverse($classes);
        $properties = array();
        foreach ($classes as $class) {
            foreach ($class->getProperties() as $property) {
                $properties[$property->getName()] = $property;
            }
        }

        return $properties;
    }

    /**
     * Get the information on a class from its instance.
     *
     * @param  object $object
     *
     * @return array
     */
    public static function getClassInformation($object)
    {
        $reflectionObject = new \ReflectionObject($object);
        $cacheId = md5("classInformation:" . $reflectionObject->getName());
        $classInfo = self::getFromCache($cacheId);
        if ($classInfo !== null) {
            return $classInfo;
        }

        $objectClasses = self::getClassesToRead($reflectionObject);
        $objectProperties = self::getProperties($objectClasses);
        $annotationReader = Configuration::getAnnotationReader();

        $classInfo = array(
            'accessProperties' => AccessReader::getAccessProperties($objectProperties, $annotationReader),
            'collectionsItemNames' => CollectionsReader::getCollectionsItemNames($objectProperties, $annotationReader),
            'associationsList' => AssociationReader::getAssociations($objectProperties, $annotationReader),
            'constraintsValidationEnabled' => ConstraintsReader::isConstraintsValidationEnabled($objectClasses, $annotationReader),
            'initialPropertiesValues' => AutoConstructReader::getPropertiesToInitialize($objectProperties, $annotationReader),
            'initializationNeededArguments' => AutoConstructReader::getConstructArguments($objectClasses, $annotationReader)
        );

        self::saveToCache($cacheId, $classInfo);

        return $classInfo;
    }

    /**
     * Get a value from the cache.
     *
     * @param  string $id
     *
     * @return mixed
     */
    public static function getFromCache($id)
    {
        $arrayCache = Configuration::getArrayCache();
        if ($arrayCache->contains($id)) {
            return $arrayCache->fetch($id);
        }

        $cacheDriver = Configuration::getCacheDriver();
        if ($cacheDriver !== null) {
            $cacheResult = $cacheDriver->fetch($id);
            if ($cacheResult !== false) {
                $arrayCache->save($id, $cacheResult);
                return $cacheResult;
            }
        }

        return null;
    }

    /**
     * Save a value to the cache.
     *
     * @param  string $id
     * @param  mixed $value
     */
    public static function saveToCache($id, $value)
    {
        $arrayCache = Configuration::getArrayCache();
        $cacheDriver = Configuration::getCacheDriver();

        $arrayCache->save($id, $value);
        if ($cacheDriver !== null) {
            $cacheDriver->save($id, $value);
        }
    }
}
