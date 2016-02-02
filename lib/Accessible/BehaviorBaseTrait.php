<?php

namespace Accessible;

use \Accessible\Reader\ConstraintsReader;
use \Accessible\MethodManager\CollectionManager;
use \Accessible\Reader\AssociationReader;

trait BehaviorBaseTrait
{
    /**
     * Validates the given value compared to given property constraints.
     * If the value is not valid, an InvalidArgumentException will be thrown.
     *
     * @param  string $property The name of the reference property.
     * @param  mixed  $value    The value to check.
     *
     * @throws \InvalidArgumentException If the value is not valid.
     */
    protected function assertPropertyValue($property, $value, $force=null)
    {
        $enabled = ($force === null) ? $this->_constraintsValidationEnabled: $force;

        if ($enabled) {
            $constraintsViolations = ConstraintsReader::validatePropertyValue($this, $property, $value);
            if ($constraintsViolations->count()) {
                $errorMessage = "Argument given is invalid; its constraints validation failed for property $property with the following messages: \"";
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
     * Update the associated property.
     *
     * @param  string $property
     * @param  object $oldValue
     * @param  object $newValue
     */
    protected function updatePropertyAssociation($property, $oldValue = null, $newValue = null)
    {
        if ($this->_associationsList === null) {
            $this->_associationsList = AssociationReader::getAssociations($this);
        }

        $association = $this->_associationsList[$property];
        if (!empty($association)) {
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
    }
}
