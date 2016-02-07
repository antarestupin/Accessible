<?php

namespace Accessible;

use \Accessible\MethodManager\MethodCallManager;
use \Accessible\Reader\AutoConstructReader;

trait AutoConstructTrait
{
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
        $this->getPropertiesInfo();

        // Initialize the properties that were defined using the Initialize / InitializeObject annotations
        $initializeValueValidationEnabled = Configuration::isInitializeValuesValidationEnabled();

        $initialValues = AutoConstructReader::getPropertiesToInitialize($this);
        foreach ($initialValues as $propertyName => $value) {
            if ($initializeValueValidationEnabled) {
                $this->assertPropertyValue($propertyName, $value);
            }

            $this->$propertyName = $value;

            if (empty($this->_collectionsItemNames['byProperty'][$propertyName])) {
                $this->updatePropertyAssociation($propertyName, array("oldValue" => null, "newValue" => $value));
            } else {
                foreach ($value as $newValue) {
                    $this->updatePropertyAssociation($propertyName, array("oldValue" => null, "newValue" => $newValue));
                }
            }
        }

        // Initialize the propeties using given arguments
        $neededArguments = AutoConstructReader::getConstructArguments($this);

        if ($neededArguments !== null && $properties !== null) {
            $numberOfNeededArguments = count($neededArguments);

            MethodCallManager::assertArgsNumber($numberOfNeededArguments, $properties);

            for ($i = 0; $i < $numberOfNeededArguments; $i++) {
                $property = $neededArguments[$i];
                $argument = $properties[$i];

                $this->assertPropertyValue($property, $argument);

                $this->$property = $argument;

                // Manage associations
                if (empty($this->_collectionsItemNames['byProperty'][$property])) {
                    $this->updatePropertyAssociation($property, array("oldValue" => null, "newValue" => $argument));
                } else {
                    foreach ($argument as $value) {
                        $this->updatePropertyAssociation($property, array("oldValue" => null, "newValue" => $value));
                    }
                }
            }
        }
    }
}
