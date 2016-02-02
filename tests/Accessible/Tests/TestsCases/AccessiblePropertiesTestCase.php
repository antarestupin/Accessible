<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessiblePropertiesTrait;
use Accessible\AutoConstructTrait;
use Accessible\Annotation\Access;
use Accessible\Annotation\ListBehavior;
use Accessible\Annotation\MapBehavior;
use Accessible\Annotation\SetBehavior;
use Accessible\Annotation\Inverted;
use Accessible\Annotation\Mapped;
use Accessible\Annotation\Initialize;

class AccessiblePropertiesTestCase
{
    use AccessiblePropertiesTrait;
    use AutoConstructTrait;

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
     * @ListBehavior
     * @Initialize({})
     */
    private $listItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @MapBehavior
     * @Initialize({})
     */
    private $mapItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @SetBehavior
     * @Initialize({})
     */
    private $setItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @ListBehavior
     * @Inverted(className=MappedTestCase::class, invertedBy="list")
     * @Initialize({})
     */
    private $testItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @ListBehavior
     * @Mapped(className=MappedTestCase::class, mappedBy="students")
     * @Initialize({})
     */
    private $courses;

    /**
     * @Access({Access::GET, Access::SET})
     * @ListBehavior
     * @Initialize({"a"})
     */
    private $initializedArray;
}
