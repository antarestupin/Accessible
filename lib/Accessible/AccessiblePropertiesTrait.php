<?php

namespace Accessible;

use \Accessible\MethodManager\CollectionManager;
use \Accessible\MethodManager\MethodCallManager;
use \Accessible\MethodManager\ListManager;
use \Accessible\MethodManager\MapManager;
use \Accessible\MethodManager\SetManager;
use \Accessible\Reader\AccessReader;
use \Accessible\Reader\AssociationReader;
use \Accessible\Reader\CollectionsReader;
use \Accessible\Reader\ConstraintsReader;

trait AccessiblePropertiesTrait
{
    /**
     * The list of access rights on each property of the object.
     *
     * @var array
     */
    private $_accessProperties;

    /**
     * The list of collection properties and their item names.
     * Ex: [
     *   "byItemName" => "user" => ["property" => "users", "behavior" => "list", "methods" => ["add", "remove"]],
     *   "byProperty" => "users" => ["itemName" => "user", "behavior" => "list", "methods" => ["add", "remove"]]
     * ]
     *
     * @var array
     */
    private $_collectionsItemNames;

    /**
     * The list of associations for each property
     * Ex: ["products" => ["property" => "cart", "association" => "inverted"]]
     *
     * @var array
     */
    private $_associationsList;

    /**
     * Indicates wether the constraints validation should be enabled or not.
     *
     * @var boolean
     */
    private $_enableConstraintsValidation;

    /**
     * Validates the given value compared to given property constraints.
     * If the value is not valid, an InvalidArgumentException will be thrown.
     *
     * @param  string $property The name of the reference property.
     * @param  mixed  $value    The value to check.
     *
     * @throws \InvalidArgumentException If the value is not valid.
     */
    protected function validatePropertyValue($property, $value)
    {
        if ($this->_enableConstraintsValidation) {
            $constraintsViolations = ConstraintsReader::validatePropertyValue($this, $property, $value);
            if ($constraintsViolations->count()) {
                $errorMessage = "Argument given is invalid; its constraints validation failed with the following messages: \"";
                $errorMessageList = array();
                foreach ($constraintsViolations as $violation) {
                    $errorMessageList[] = $violation->getMessage();
                }
                $errorMessage .= implode("\", \n\"", $errorMessageList)."\".";

                throw new \InvalidArgumentException($errorMessage);
            }
        }
    }

    /**
     * This function will be called each time a getter or a setter that is not
     * already defined in the class is called.
     *
     * @param  string $name The name of the called function.
     *                      It must be a getter or a setter.
     * @param  array  $args The array of arguments for the called function.
     *                      It should be empty for a getter call,
     *                      and should have one item for a setter call.
     *
     * @return mixed    The value that should be returned by the function called if it is a getter,
     *                  the object itself if the function called is a setter.
     *
     * @throws \BadMethodCallException      When the method called is neither a getter nor a setter,
     *         						   		or if the access right has not be given for this method,
     *         						     	or if the method is a setter called without argument.
     * @throws \InvalidArgumentException    When the argument given to the method called (as a setter)
     *         								does not satisfy the constraints attached to the property
     *         								to modify.
     */
    function __call($name, array $args)
    {
        // if we don't already know the access properties, get them
        if ($this->_accessProperties === null) {
            $this->_accessProperties = AccessReader::getAccessProperties($this);
        }

        // if we don't already have the list of collections item names, get it
        if ($this->_collectionsItemNames === null) {
            $this->_collectionsItemNames = CollectionsReader::getCollectionsItemNames($this);
        }

        // if we don't already have the list of associations, get it
        if ($this->_associationsList === null) {
            $this->_associationsList = AssociationReader::getAssociations($this);
        }

        // if we don't already know wether the constraints should be validated
        if ($this->_enableConstraintsValidation === null) {
            $this->_enableConstraintsValidation = ConstraintsReader::isConstraintsValidationEnabled($this);
        }

        // check that the called method is a valid method name
        // also get the call type and the property to access
        $callIsValid = preg_match("/(set|get|is|add|remove)([A-Z].*)/", $name, $pregMatches);
        if (!$callIsValid) {
            throw new \BadMethodCallException("Method $name does not exist.");
        }

        $method = $pregMatches[1];
        $property = strtolower(substr($pregMatches[2], 0, 1)).substr($pregMatches[2], 1);
        $collectionProperties = null;
        if (in_array($method, array('add', 'remove'))) {
            $collectionProperties = $this->_collectionsItemNames['byItemName'][$property];
            $property = $collectionProperties['property'];
        }

        // check that the method is accepted by the targeted property
        if (
            empty($this->_accessProperties[$property])
            || !in_array($method, $this->_accessProperties[$property])
        ) {
            throw new \BadMethodCallException("Method $name does not exist.");
        }

        $association = $this->_associationsList[$property];
        $oldValue = null;
        $newValue = null;

        switch($method) {
            case 'get':
            case 'is':
                return $this->$property;
                break;

            case 'set':
                // a setter should have exactly one argument
                MethodCallManager::assertArgsNumber(1, $args);
                // we set a collection here if there is an association with it
                if (
                    !empty($this->_collectionsItemNames['byProperty'][$property])
                    && !(empty($association))
                ) {
                    $itemName = $this->_collectionsItemNames['byProperty'][$property]['itemName'];
                    $propertyAddMethod = 'add'.strtoupper(substr($itemName, 0, 1)).substr($itemName, 1);
                    $propertyRemoveMethod = 'remove'.strtoupper(substr($itemName, 0, 1)).substr($itemName, 1);

                    foreach ($this->$property as $item) {
                        $this->$propertyRemoveMethod($item);
                    }
                    foreach ($args[0] as $item) {
                        $this->$propertyAddMethod($item);
                    }
                }
                // we set a regular property here
                else {
                    $oldValue = $this->$property;
                    $newValue = $args[0];
                    // check that the setter argument respects the property constraints
                    $this->validatePropertyValue($property, $newValue);

                    if ($oldValue !== $newValue) {
                        $this->$property = $newValue;
                    }
                }
                break;

            case 'add':
                switch ($collectionProperties['behavior']) {
                    case 'list':
                        ListManager::add($this->$property, $args);
                        $newValue = $args[0];
                        break;
                    case 'map':
                        MapManager::add($this->$property, $args);
                        break;
                    case 'set':
                        SetManager::add($this->$property, $args);
                        $newValue = $args[0];
                        break;
                }
                break;

            case 'remove':
                switch ($collectionProperties['behavior']) {
                    case 'list':
                        ListManager::remove($this->$property, $args);
                        $oldValue = $args[0];
                        break;
                    case 'map':
                        MapManager::remove($this->$property, $args);
                        break;
                    case 'set':
                        SetManager::remove($this->$property, $args);
                        $oldValue = $args[0];
                        break;
                }
                break;
        }

        // manage associations
        if (
            in_array($method, array('set', 'add', 'remove'))
            && !empty($association)
        ) {
            $associatedProperty = $association['property'];
            switch ($association['association']) {
                case 'inverted':
                    $invertedGetMethod = 'get'.strtoupper(substr($associatedProperty, 0, 1)).substr($associatedProperty, 1);
                    $invertedSetMethod = 'set'.strtoupper(substr($associatedProperty, 0, 1)).substr($associatedProperty, 1);
                    if ($oldValue !== null && $oldValue->$invertedGetMethod() === $this) {
                        $oldValue->$invertedSetMethod(null);
                    }
                    if ($newValue !== null && $newValue->$invertedGetMethod() !== $this) {
                        $newValue->$invertedSetMethod($this);
                    }
                    break;
                case 'mapped':
                    $itemName = $association['itemName'];
                    $mappedGetMethod = 'get'.strtoupper(substr($associatedProperty, 0, 1)).substr($associatedProperty, 1);
                    $mappedAddMethod = 'add'.strtoupper(substr($itemName, 0, 1)).substr($itemName, 1);
                    $mappedRemoveMethod = 'remove'.strtoupper(substr($itemName, 0, 1)).substr($itemName, 1);

                    if ($oldValue !== null && CollectionManager::collectionContains($this, $oldValue->$mappedGetMethod())) {
                        $oldValue->$mappedRemoveMethod($this);
                    }
                    if ($newValue !== null && !CollectionManager::collectionContains($this, $newValue->$mappedGetMethod())) {
                        $newValue->$mappedAddMethod($this);
                    }
                    break;
            }
        }

        return $this;
    }
}
