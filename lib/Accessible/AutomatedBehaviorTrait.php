<?php

namespace Accessible;

trait AutomatedBehaviorTrait
{
    use AccessiblePropertiesTrait, AutoConstructTrait {
        AccessiblePropertiesTrait::assertPropertyValue insteadof AutoConstructTrait;
        AccessiblePropertiesTrait::updatePropertyAssociation insteadof AutoConstructTrait;
    }
}
