<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AutomatedBehaviorTrait;
use Accessible\Annotation\Access;
use Accessible\Annotation as Behavior;

class AssociationTestCase
{
    use AutomatedBehaviorTrait;

    /**
     * @Access({Access::GET, Access::SET})
     */
    private $invertedOneToOneProperty;

    /**
     * @Access({Access::GET, Access::SET})
     */
    private $mappedManyToOneProperty;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\Referenced(className=BaseTestCase::class, propertyName="invertedReversedOneToOneProperty")
     */
    private $invertedReversedOneToOneProperty;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\InCollection(className=BaseTestCase::class, propertyName="invertedReversedManyToOnePropertyItems")
     */
    private $mappedReversedManyToOneProperty;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\SetBehavior
     * @Behavior\Initialize({})
     */
    private $mappedManyToManyPropertyItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\SetBehavior
     * @Behavior\Initialize({})
     * @Behavior\InCollection(className=BaseTestCase::class, propertyName="mappedReversedManyToManyPropertyItems")
     */
    private $mappedReversedManyToManyPropertyItems;
}
