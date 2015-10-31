<?php

namespace Accessible;

use Symfony\Component\Validator\Validation;
use \Accessible\Annotations\Access;

trait Accessible
{
    /**
     * The list of access rights on each property of the object.
     *
     * @var array
     */
    private $_accessProperties;

    /**
     * The constraints validator used to validate any new value for each property of the object.
     * @var [type]
     */
    private $_constraintsValidator;

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
        if ($this->_accessProperties === null || $this->_constraintsValidator === null) {
            $accessReader = new AccessReader();
            $this->_accessProperties = $accessReader->getAccessProperties($this);

            $this->_constraintsValidator = Validation::createValidatorBuilder()
                ->enableAnnotationMapping()
                ->getValidator();
        }

        if (preg_match("/(set|get|is|has)([A-Z].*)/", $name, $pregMatches)) {
            $method = $pregMatches[1];
            $property = strtolower(substr($pregMatches[2], 0, 1)).substr($pregMatches[2], 1);

            if (!in_array($method, $this->_accessProperties[$property])) {
                throw new \BadMethodCallException("Method $name does not exist.");
            }

            switch($method) {
                case 'get':
                case 'is':
                case 'has':
                    return $this->$property;
                    break;
                case 'set':
                    if (sizeof($args) !== 1) {
                        throw new \BadMethodCallException("One argument is needed for method $name.");
                    }

                    $arg = $args[0];

                    $constraintsViolations = $this->_constraintsValidator->validatePropertyValue($this, $property, $arg);
                    if ($constraintsViolations->count()) {
                        $errorMessage = $constraintsViolations[0]->getMessage();
                        throw new \InvalidArgumentException("Argument given for method $name is invalid; its constraints validation failed with the message \"".$errorMessage."\".");
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
