<?php

namespace Accessible;

use \Accessible\Reader\AutoConstructReader;
use \Accessible\Reader\ConstraintsReader;

trait AutoConstructTrait
{
    /**
     * Initializes the object according to its class specification and given arguments.
     */
    public function __construct()
    {
        // Initialize the properties that were defined using the Initialize / InitializeObject annotations
        $initializeValueValidationEnabled = Configuration::isInitializeValuesValidationEnabled();
        $constraintsValidationEnabled = ConstraintsReader::isConstraintsValidationEnabled($this);

        $initialValues = AutoConstructReader::getPropertiesToInitialize($this);
        foreach ($initialValues as $propertyName => $value) {
            if ($initializeValueValidationEnabled && $constraintsValidationEnabled) {
                $constraintsViolations = ConstraintsReader::validatePropertyValue($this, $propertyName, $value);
                if ($constraintsViolations->count()) {
                    $errorMessage = "Object Initialization failed; the property $propertyName has been specified with a wrong value; \
                    its constraints validation failed with the following messages: \"";
                    $errorMessageList = array();
                    foreach ($constraintsViolations as $violation) {
                        $errorMessageList[] = $violation->getMessage();
                    }
                    $errorMessage .= implode("\", \"", $errorMessageList)."\".";

                    throw new \LogicException($errorMessage);
                }
            }

            $this->$propertyName = $value;
        }

        // Initialize the propeties using given arguments
        $neededArguments = AutoConstructReader::getConstructArguments($this);

        if ($neededArguments !== null) {
            $givenArguments = func_get_args();
            $numberOfNeededArguments = count($neededArguments);
            $numberOfGivenArguments = count($givenArguments);

            if ($numberOfGivenArguments !== $numberOfNeededArguments) {
                throw new \BadMethodCallException("Wrong number of arguments given to the constructor.");
            }

            for ($i = 0; $i < $numberOfNeededArguments; $i++) {
                $property = $neededArguments[$i];
                $argument = $givenArguments[$i];

                if ($constraintsValidationEnabled) {
                    $constraintsViolations = ConstraintsReader::validatePropertyValue($this, $property, $argument);
                    if ($constraintsViolations->count()) {
                        $errorMessage = "Object Initialization failed; argument given for the property $property is invalid; \
                        its constraints validation failed with the following messages: \"";
                        $errorMessageList = array();
                        foreach ($constraintsViolations as $violation) {
                            $errorMessageList[] = $violation->getMessage();
                        }
                        $errorMessage .= implode("\", \"", $errorMessageList)."\".";

                        throw new \InvalidArgumentException($errorMessage);
                    }
                }

                $this->$property = $argument;
            }
        }
    }
}
