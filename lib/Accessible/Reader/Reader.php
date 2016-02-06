<?php

namespace Accessible\Reader;

use \Accessible\Configuration;

class Reader
{
    /**
     * The name of the annotation classes that define a collection behavior.
     *
     * @var string
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

        return $objectClasses;
    }

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
