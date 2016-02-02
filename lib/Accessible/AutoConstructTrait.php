<?php

namespace Accessible;

use \Accessible\MethodManager\MethodCallManager;
use \Accessible\Reader\AutoConstructReader;
use \Accessible\Reader\AccessReader;
use \Accessible\Reader\AssociationReader;
use \Accessible\Reader\CollectionsReader;
use \Accessible\Reader\ConstraintsReader;

trait AutoConstructTrait
{
    use BehaviorBaseTrait;

    /**
     * Directly calls the initialization method.
     */
    public function __construct()
    {
        $this->initializeProperties(func_get_args());
    }

    /**
     * Initializes the object according to its class specification and given arguments.
     *
     * @param array $properties The values to give to the properties.
     */
    protected function initializeProperties($properties = null)
    {
        // Initialize the properties that were defined using the Initialize / InitializeObject annotations
        $initializeValueValidationEnabled = Configuration::isInitializeValuesValidationEnabled();
        $constraintsValidationEnabled = ConstraintsReader::isConstraintsValidationEnabled($this);

        // Get the list of collections
        $collectionsItemNames = CollectionsReader::getCollectionsItemNames($this);

        $initialValues = AutoConstructReader::getPropertiesToInitialize($this);
        foreach ($initialValues as $propertyName => $value) {
            if ($initializeValueValidationEnabled && $constraintsValidationEnabled) {
                $this->assertPropertyValue($propertyName, $value, true);
            }

            $this->$propertyName = $value;

            if (!empty($this->_collectionsItemNames['byProperty'][$propertyName])) {
                $this->updatePropertyAssociation($propertyName, null, $value);
            }
        }

        // Initialize the propeties using given arguments
        $neededArguments = AutoConstructReader::getConstructArguments($this);

        if ($neededArguments !== null) {
            $givenArguments = $properties;
            $numberOfNeededArguments = count($neededArguments);

            MethodCallManager::assertArgsNumber($numberOfNeededArguments, $givenArguments);

            for ($i = 0; $i < $numberOfNeededArguments; $i++) {
                $property = $neededArguments[$i];
                $argument = $givenArguments[$i];

                if ($constraintsValidationEnabled) {
                    $this->assertPropertyValue($property, $argument, true);
                }

                $this->$property = $argument;

                if (!empty($this->_collectionsItemNames['byProperty'][$property])) {
                    $this->updatePropertyAssociation($property, null, $argument);
                }
            }
        }
    }
}
