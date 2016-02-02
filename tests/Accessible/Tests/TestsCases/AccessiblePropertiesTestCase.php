<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessiblePropertiesTrait;
use Accessible\Annotation\Access;
use Accessible\Annotation\ListBehavior;
use Accessible\Annotation\MapBehavior;
use Accessible\Annotation\SetBehavior;
use Accessible\Annotation\Inverted;
use Accessible\Annotation\Mapped;

class AccessiblePropertiesTestCase
{
    use AccessiblePropertiesTrait;

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
     */
    private $listItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @MapBehavior
     */
    private $mapItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @SetBehavior
     */
    private $setItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @ListBehavior
     * @Inverted(className=MappedTestCase::class, invertedBy="list")
     */
    private $testItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @ListBehavior
     * @Mapped(className=MappedTestCase::class, mappedBy="students")
     */
    private $courses;

    public function __construct()
    {
        $this->listItems = array();
        $this->mapItems = array();
        $this->setItems = array();
        $this->testItems = array();
        $this->courses = array();
    }
}
