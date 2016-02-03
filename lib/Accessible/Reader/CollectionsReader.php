<?php

namespace Accessible\Reader;

use \Accessible\Configuration;
use Doctrine\Common\Inflector\Inflector;

class CollectionsReader extends Reader
{
    /**
     * Get a list linking an item name with the property it refers to and what kind of collection it is.
     * Ex: [
     *   "byItemName" => "user" => ["property" => "users", "behavior" => "list", "methods" => ["add", "remove"]],
     *   "byProperty" => "users" => ["itemName" => "user", "behavior" => "list", "methods" => ["add", "remove"]]
     * ]
     *
     * @param object $object The object to read.
     *
     * @return array The described list.
     */
    public static function getCollectionsItemNames($object)
    {
        $reflectionObject = new \ReflectionObject($object);
        $cacheId = md5("collectionItemNames:" . $reflectionObject->getName());
        $objectCollectionsItemNames = self::getFromCache($cacheId);
        if ($objectCollectionsItemNames !== null) {
            return $objectCollectionsItemNames;
        }

        $objectCollectionsItemNames = array(
            "byProperty" => array(),
            "byItemName" => array()
        );
        $objectClasses = self::getClassesToRead($reflectionObject);
        array_reverse($objectClasses);

        $annotationReader = Configuration::getAnnotationReader();
        foreach($objectClasses as $class) {
            foreach ($class->getProperties() as $property) {
                $propertyName = $property->getName();
                $annotation = null;
                $behavior = null;
                foreach (self::$collectionAnnotationClasses as $annotationBehavior => $annotationClass) {
                    $annotation = $annotationReader->getPropertyAnnotation($property, $annotationClass);
                    if ($annotation !== null) {
                        $behavior = $annotationBehavior;
                        break;
                    }
                }

                if ($annotation !== null) {
                    // get the item name, or deduce it (singularize the property name)
                    $itemName = $annotation->getItemName();
                    if ($itemName === null) {
                        $itemName = Inflector::singularize($propertyName);
                    }

                    $objectCollectionsItemNames["byItemName"][$itemName] = array(
                        "property" => $propertyName,
                        "behavior" => $behavior,
                        "methods" => $annotation->getMethods()
                    );
                    $objectCollectionsItemNames["byProperty"][$propertyName] = array(
                        "itemName" => $itemName,
                        "behavior" => $behavior,
                        "methods" => $annotation->getMethods()
                    );
                }
            }
        }

        self::saveToCache($cacheId, $objectCollectionsItemNames);

        return $objectCollectionsItemNames;
    }
}
