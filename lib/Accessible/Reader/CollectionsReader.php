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
     * @param array  $properties The properties of the object to read.
     * @param Reader $objectClasses The annotation reader to use.
     *
     * @return array The described list.
     */
    public static function getCollectionsItemNames($properties, $annotationReader)
    {
        $objectCollectionsItemNames = array(
            "byProperty" => array(),
            "byItemName" => array()
        );

        foreach ($properties as $propertyName => $property) {
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

        return $objectCollectionsItemNames;
    }
}
