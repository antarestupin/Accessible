<?php

namespace Accessible\Reader;

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
    protected static function getClassesToRead(\ReflectionObject $reflectionObject)
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
}
