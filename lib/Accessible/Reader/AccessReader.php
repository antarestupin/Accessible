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
        $cacheDriver = Configuration::getCacheDriver();
        $cacheId = "getAccessProperties:" . $reflectionObject->getName();
        $objectAccessProperties = $cacheDriver->fetch($cacheId);
        if ($objectAccessProperties !== false) {
            return $objectAccessProperties;
        }

        $objectAccessProperties = array();
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

        $cacheDriver->save($cacheId, $objectAccessProperties);

        return $objectAccessProperties;
    }
}
