<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AutomatedBehaviorTrait;
use Accessible\Annotation\Access;
use Accessible\Annotation as Behavior;

class AccessiblePropertiesTestCase
{
    use AutomatedBehaviorTrait;

    /**
     * @Access({Access::GET, Access::SET})
     */
    private $foo = "foo";

    /**
     * @Access({Access::IS})
     */
    private $bar = "bar";

    private $notAccessibleProperty;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\ListBehavior
     * @Behavior\Initialize({})
     */
    private $listItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\MapBehavior
     * @Behavior\Initialize({})
     */
    private $mapItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\SetBehavior
     * @Behavior\Initialize({})
     */
    private $setItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\ListBehavior
     * @Behavior\Inverted(className=MappedTestCase::class, invertedBy="list")
     * @Behavior\Initialize({})
     */
    private $testItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\ListBehavior
     * @Behavior\Mapped(className=MappedTestCase::class, mappedBy="students")
     * @Behavior\Initialize({})
     */
    private $courses;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\ListBehavior
     * @Behavior\Initialize({"a"})
     */
    private $initializedArray;
}
