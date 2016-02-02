<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AutomatedBehaviorTrait;
use Accessible\Annotation\Access;
use Accessible\Annotation as Behavior;

class MappedTestCase
{
    use AutomatedBehaviorTrait;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\Mapped(className=AccessiblePropertiesTestCase::class, mappedBy="testItems")
     */
    private $list;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\ListBehavior
     * @Behavior\Mapped(className=AccessiblePropertiesTestCase::class, mappedBy="courses")
     */
    private $students;

    public function __construct()
    {
        $this->students = array();
    }
}
