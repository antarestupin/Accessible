<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessiblePropertiesTrait;
use Accessible\Annotation\Access;
use Accessible\Annotation\Mapped;
use Accessible\Annotation\ListBehavior;

class MappedTestCase
{
    use AccessiblePropertiesTrait;

    /**
     * @Access({Access::GET, Access::SET})
     * @Mapped(className=AccessiblePropertiesTestCase::class, mappedBy="testItems")
     */
    private $list;

    /**
     * @Access({Access::GET, Access::SET})
     * @ListBehavior
     * @Mapped(className=AccessiblePropertiesTestCase::class, mappedBy="courses")
     */
    private $students;

    public function __construct()
    {
        $this->students = array();
    }
}
