<?php

namespace Accessible\Reader;

use \Accessible\Configuration;

class AccessReader extends Reader
{
    /**
     * The name of the annotation class that define the properties access.
     *
     * @var string
     */
    private static $accessAnnotationClass = "Accessible\\Annotation\\Access";

    /**
     * Get a list of properties and the access that are given to them for given object.
     *
     * @param object $object The object to read.
     *
     * @return array The list of properties and their access.
     */
    public static function getAccessProperties($object)
    {
        $reflectionObject = new \ReflectionObject($object);
        $cacheId = md5("accessProperties:" . $reflectionObject->getName());
        $objectAccessProperties = self::getFromCache($cacheId);
        if ($objectAccessProperties !== null) {
            return $objectAccessProperties;
        }

        $objectAccessProperties = array();
        $objectClasses = self::getClassesToRead($reflectionObject);
        array_reverse($objectClasses);

        $annotationReader = Configuration::getAnnotationReader();
        foreach ($objectClasses as $class) {
            foreach ($class->getProperties() as $property) {
                $propertyName = $property->getName();

                if (empty($objectAccessProperties[$propertyName])) {
                    $objectAccessProperties[$propertyName] = array();
                }

                // Getters / Setters related annotations
                $propertyAccessAnnotation = $annotationReader->getPropertyAnnotation($property, self::$accessAnnotationClass);

                $accessProperties = array();
                if ($propertyAccessAnnotation !== null) {
                    $accessProperties = $propertyAccessAnnotation->getAccessProperties();
                }

                // Collection related annotations
                $collectionAnnotation = null;
                foreach (self::$collectionAnnotationClasses as $annotationBehavior => $annotationClass) {
                    $collectionAnnotation = $annotationReader->getPropertyAnnotation($property, $annotationClass);
                    if ($collectionAnnotation !== null) {
                        break;
                    }
                }

                $collectionMethods = array();
                if ($collectionAnnotation !== null) {
                    $collectionMethods = $collectionAnnotation->getMethods();
                }

                // Merge and save the two arrays
                $objectAccessProperties[$propertyName] = array_merge($accessProperties, $collectionMethods);
            }
        }

        self::saveToCache($cacheId, $objectAccessProperties);

        return $objectAccessProperties;
    }
}
