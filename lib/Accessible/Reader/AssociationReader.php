<?php

namespace Accessible\Reader;

use \Accessible\Configuration;
use Doctrine\Common\Inflector\Inflector;

class AssociationReader extends Reader
{
    /**
     * The name of the annotation classes that define an association.
     *
     * @var string
     */
    private static $associationAnnotationClasses = array(
        "inverted" => "Accessible\\Annotation\\Inverted",
        "mapped" => "Accessible\\Annotation\\Mapped",
    );

    /**
     * Get a list linking a property with association between the property's class and the current class.
     * Ex: ["products" => ["property" => "cart", "association" => "inverted"]]
     *
     * @param object $object The object to read.
     *
     * @return array The described list.
     */
    public static function getAssociations($object)
    {
        $reflectionObject = new \ReflectionObject($object);
        $cacheId = md5("association:" . $reflectionObject->getName());
        $objectAssociations = self::getFromCache($cacheId);
        if ($objectAssociations !== null) {
            return $objectAssociations;
        }

        $objectAssociations = array();
        $objectClasses = self::getClassesToRead($reflectionObject);
        array_reverse($objectClasses);

        $annotationReader = Configuration::getAnnotationReader();
        foreach($objectClasses as $class) {
            foreach ($class->getProperties() as $property) {
                $propertyName = $property->getName();
                $annotation = null;
                $associationType = null;
                foreach (self::$associationAnnotationClasses as $annotationAssociationType => $annotationClass) {
                    $annotation = $annotationReader->getPropertyAnnotation($property, $annotationClass);
                    if ($annotation !== null) {
                        $associationType = $annotationAssociationType;
                        break;
                    }
                }

                if ($annotation !== null) {
                    // get the item name
                    $associatedPropertyName = $annotation->getAssociatedProperty();

                    $objectAssociations[$propertyName] = array(
                        "property" => $associatedPropertyName,
                        "association" => $associationType
                    );

                    // if this is a mapped association, get the item name of the current class
                    if ($associationType === "mapped") {
                        $associatedClass = new \ReflectionClass($annotation->getClassName());
                        $associatedPropertyReflection = $associatedClass->getProperty($associatedPropertyName);

                        $itemName = null;
                        foreach (self::$collectionAnnotationClasses as $annotationBehavior => $annotationClass) {
                            $collectionAnnotation = $annotationReader->getPropertyAnnotation($associatedPropertyReflection, $annotationClass);
                            if ($collectionAnnotation !== null) {
                                $itemName = $collectionAnnotation->getItemName();
                                break;
                            }
                        }
                        if ($itemName === null) {
                            $itemName = Inflector::singularize($associatedPropertyName);
                        }

                        $objectAssociations[$propertyName]["itemName"] = $itemName;
                    }
                } else {
                    $objectAssociations[$propertyName] = null;
                }
            }
        }

        self::saveToCache($cacheId, $objectAssociations);

        return $objectAssociations;
    }
}
