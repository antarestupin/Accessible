<?php

namespace Accessible;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use \Accessible\Annotations\Access;

class AccessReader
{
    /**
     * The annotations reader used to know the access.
     *
     * @var Doctrine\Common\Annotations\Reader
     */
    private $reader;

    /**
     * The name of the annotation class that define the properties access.
     *
     * @var string
     */
    private $annotationClass = "Accessible\\Annotations\\Access";

    /**
     * Initializes the access reader.
     *
     * @param Reader $reader The annotation reader the object will use.
     */
    public function __construct(Reader $reader = null)
    {
        if ($reader === null) {
            $reader = new CachedReader(new AnnotationReader(), new ArrayCache());
        }

        $this->reader = $reader;
    }

    /**
     * Get a list of properties and the access that are given to them for given object.
     *
     * @param  object $object The object to read.
     *
     * @return array The list of properties and their access.
     */
    public function getAccessProperties($object)
    {
        $objectAccessProperties = array();

        $reflectionObject = new \ReflectionObject($object);

        foreach ($reflectionObject->getProperties() as $property) {
            $annotation = $this->reader->getPropertyAnnotation($property, $this->annotationClass);
            $propertyName = $property->getName();

            $objectAccessProperties[$propertyName] = array();
            if ($annotation !== null) {
                $accessProperties = $annotation->getAccessProperties();
                $objectAccessProperties[$propertyName] = $accessProperties;
            }
        }

        return $objectAccessProperties;
    }
}
