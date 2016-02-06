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
     * @Behavior\Inverted(className=BaseTestCase::class, invertedBy="invertedReversedOneToOneProperty")
     */
    private $invertedReversedOneToOneProperty;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\Mapped(className=BaseTestCase::class, mappedBy="invertedReversedManyToOnePropertyItems")
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
     * @Behavior\Mapped(className=BaseTestCase::class, mappedBy="mappedReversedManyToManyPropertyItems")
     */
    private $mappedReversedManyToManyPropertyItems;
}
