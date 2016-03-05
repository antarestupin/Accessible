<?php

namespace Accessible\Reader;

use Doctrine\Common\Inflector\Inflector;

class AssociationReader extends Reader
{
    /**
     * The name of the annotation classes that define an association.
     *
     * @var array<string>
     */
    private static $associationAnnotationClasses = array(
        "inverted" => "Accessible\\Annotation\\Referenced",
        "mapped" => "Accessible\\Annotation\\InCollection",
    );

    /**
     * Get a list linking a property with association between the property's class and the current class.
     * Ex: ["products" => ["property" => "cart", "association" => "inverted"]]
     *
     * @param array  $properties The properties of the object to read.
     * @param \Doctrine\Common\Annotations\Reader $annotationReader The annotation reader to use.
     *
     * @return array The described list.
     */
    public static function getAssociations($properties, $annotationReader)
    {
        $objectAssociations = array();

        foreach ($properties as $propertyName => $property) {
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

        return $objectAssociations;
    }
}
