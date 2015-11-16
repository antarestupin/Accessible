<?php

namespace Accessible;

use \Accessible\Reader\AccessReader;
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
     * Indicates wether the constraints validation should be enabled or not.
     *
     * @var boolean
     */
    private $_enableConstraintsValidation;

    /**
     * Validates the given value compared to given property constraints.
     * If the value is valid, a call to `count` to the object returned
     * by this method should give 0.
     *
     * @param  string $property The name of the reference property.
     * @param  mixed  $value    The value to check.
     *
     * @return Symfony\Component\Validator\ConstraintViolationList
     *         The list of constraints violations the check returns.
     */
    protected function validatePropertyValue($property, $value)
    {
        return ConstraintsReader::validatePropertyValue($this, $property, $value);
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

        // if we don't already know wether the constraints should be validated
        if ($this->_enableConstraintsValidation === null) {
            $this->_enableConstraintsValidation = ConstraintsReader::isConstraintsValidationEnabled($this);
        }

        // check that the called method is a getter or a setter
        if (preg_match("/(set|get|is|has)([A-Z].*)/", $name, $pregMatches)) {
            $method = $pregMatches[1];
            $property = strtolower(substr($pregMatches[2], 0, 1)).substr($pregMatches[2], 1);

            // check that the getter/setter is accepted by the targeted property
            if (
                empty($this->_accessProperties[$property])
                || !in_array($method, $this->_accessProperties[$property])
            ) {
                throw new \BadMethodCallException("Method $name does not exist.");
            }

            switch($method) {
                // getter
                case 'get':
                case 'is':
                case 'has':
                    return $this->$property;
                    break;
                // setter
                case 'set':
                    // a setter should have exactly one argument
                    if (sizeof($args) !== 1) {
                        throw new \BadMethodCallException("One argument is needed for method $name.");
                    }

                    $arg = $args[0];

                    // check that the setter argument respects the property constraints
                    if ($this->_enableConstraintsValidation) {
                        $constraintsViolations = $this->validatePropertyValue($property, $arg);
                        if ($constraintsViolations->count()) {
                            $errorMessage = "Argument given for method $name is invalid; its constraints validation failed with the following messages: \"";
                            $errorMessageList = array();
                            foreach ($constraintsViolations as $violation) {
                                $errorMessageList[] = $violation->getMessage();
                            }
                            $errorMessage .= implode("\", \"", $errorMessageList)."\".";

                            throw new \InvalidArgumentException($errorMessage);
                        }
                    }

                    $this->$property = $arg;
                    return $this;
                    break;
            }
        } else {
            throw new \BadMethodCallException("Method $name does not exist.");
        }
    }
}
