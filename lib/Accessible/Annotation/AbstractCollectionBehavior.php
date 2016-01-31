<?php

namespace Accessible\Annotation;

/**
 * This class is the base of the collection-based behavior annotations.
 */
abstract class AbstractCollectionBehavior
{
    /**
     * The property can be accessed through an addX method.
     *
     * @var string
     */
    const ADD = "add";

    /**
     * The property can be accessed through a removeX method.
     *
     * @var string
     */
    const REMOVE = "remove";

    /**
     * The name of an item in the collection. It is used to deduce the methods names.
     * If it is not defined, it will be the singularized version of the property name.
     *
     * @var string
     */
    protected $itemName;

    /**
     * The list of methods that can be used with the collection.
     *
     * @var array<string>
     */
    protected $methods;

    /**
     * The default methods that can be used.
     *
     * @var array<string>
     */
    protected $defaultMethods;

    /**
     * Initializes the annotation.
     *
     * @param mixed $values The annotation's parameters.
     */
    public function __construct($values)
    {
        $defaults = array(
            'itemName' => null,
            'methods' => $this->defaultMethods
        );

        foreach ($defaults as $property => $defaultValue) {
            $this->$property = (empty($values[$property])) ? $defaultValue: $values[$property];
        }
    }

    public function getItemName()
    {
        return $this->itemName;
    }

    public function getMethods()
    {
        return $this->methods;
    }
}
