<?php

namespace Accessible;

use \Accessible\MethodManager\CollectionManager;
use \Accessible\Reader\Reader;
use \Accessible\Reader\ConstraintsReader;

trait AutomatedBehaviorTrait
{
    use AutoConstructTrait, AutoMethodsTrait;

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
    private $_constraintsValidationEnabled;

    /**
     * The initial values of the properties that use Initialize and InitializeObject annotations.
     *
     * @var array
     */
    private $_initialPropertiesValues;

    /**
     * The arguments needed for the constructor.
     *
     * @var array
     */
    private $_initializationNeededArguments;

    /**
     * Indicates wether getPropertiesInfo() has been called or not.
     *
     * @var boolean
     */
    private $_automatedBehaviorInitialized = false;

    /**
     * Indicates if the properties constraints validation is enabled.
     *
     * @return boolean
     */
    public function isPropertiesConstraintsValidationEnabled()
    {
        return $this->_constraintsValidationEnabled;
    }

    /**
     * Enable (or disable) the properties constraints validation.
     *
     * @param boolean $enabled
     */
    public function setPropertiesConstraintsValidationEnabled($enabled = true)
    {
        $this->_constraintsValidationEnabled = $enabled;

        return $this;
    }

    /**
     * Disable (or enable) the properties constraints validation.
     *
     * @param boolean $disabled
     */
    public function setPropertiesConstraintsValidationDisabled($disabled = true)
    {
        $this->_constraintsValidationEnabled = !$disabled;

        return $this;
    }

    /**
     * Validates the given value compared to given property constraints.
     * If the value is not valid, an InvalidArgumentException will be thrown.
     *
     * @param  string $property The name of the reference property.
     * @param  mixed  $value    The value to check.
     *
     * @throws \InvalidArgumentException If the value is not valid.
     */
    protected function assertPropertyValue($property, $value)
    {
        $this->getPropertiesInfo();

        if ($this->_constraintsValidationEnabled) {
            $constraintsViolations = ConstraintsReader::validatePropertyValue($this, $property, $value);
            if ($constraintsViolations->count()) {
                $errorMessage = "Argument given is invalid; its constraints validation failed for property $property with the following messages: \"";
                $errorMessageList = array();
                foreach ($constraintsViolations as $violation) {
                    $errorMessageList[] = $violation->getMessage();
                }
                $errorMessage .= implode("\", \n\"", $errorMessageList) . "\".";

                throw new \InvalidArgumentException($errorMessage);
            }
        }
    }

    /**
     * Update the property associated to the given property.
     * You can pass the old or the new value given to the property.
     *
     * @param  string $property The property of the current class to update
     * @param  object $values   An array of old a new value under the following form:
     *                          ['oldValue' => $oldvalue, 'newValue' => $newValue]
     *                          If one of theses values is not given, it will simply not be updated.
     */
    protected function updatePropertyAssociation($property, array $values)
    {
        $this->getPropertiesInfo();

        $oldValue = empty($values['oldValue']) ? null : $values['oldValue'];
        $newValue = empty($values['newValue']) ? null : $values['newValue'];

        $association = $this->_associationsList[$property];
        if (!empty($association)) {
            $associatedProperty = $association['property'];
            switch ($association['association']) {
                case 'inverted':
                    $invertedGetMethod = 'get' . ucfirst($associatedProperty);
                    $invertedSetMethod = 'set' . ucfirst($associatedProperty);
                    if ($oldValue !== null && $oldValue->$invertedGetMethod() === $this) {
                        $oldValue->$invertedSetMethod(null);
                    }
                    if ($newValue !== null && $newValue->$invertedGetMethod() !== $this) {
                        $newValue->$invertedSetMethod($this);
                    }
                    break;

                case 'mapped':
                    $itemName = $association['itemName'];
                    $mappedGetMethod = 'get' . ucfirst($associatedProperty);
                    $mappedAddMethod = 'add' . ucfirst($itemName);
                    $mappedRemoveMethod = 'remove' . ucfirst($itemName);

                    if ($oldValue !== null && CollectionManager::collectionContains($this, $oldValue->$mappedGetMethod())) {
                        $oldValue->$mappedRemoveMethod($this);
                    }
                    if ($newValue !== null && !CollectionManager::collectionContains($this, $newValue->$mappedGetMethod())) {
                        $newValue->$mappedAddMethod($this);
                    }
                    break;
            }
        }
    }

    /**
     * Get every information needed from this class.
     */
    private function getPropertiesInfo()
    {
        if (!$this->_automatedBehaviorInitialized) {
            $classInfo = Reader::getClassInformation($this);

            $this->_accessProperties = $classInfo['accessProperties'];
            $this->_collectionsItemNames = $classInfo['collectionsItemNames'];
            $this->_associationsList = $classInfo['associationsList'];
            $this->_constraintsValidationEnabled = $classInfo['constraintsValidationEnabled'];
            $this->_initialPropertiesValues = $classInfo['initialPropertiesValues'];
            $this->_initializationNeededArguments = $classInfo['initializationNeededArguments'];

            $this->_automatedBehaviorInitialized = true;
        }
    }
}
