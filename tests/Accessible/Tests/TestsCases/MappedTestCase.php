<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessiblePropertiesTrait;
use Accessible\Annotation\Access;
use Accessible\Annotation\Mapped;

class MappedTestCase
{
    use AccessiblePropertiesTrait;

    /**
     * @Access({Access::GET, Access::SET})
     * @Mapped(className=AccessiblePropertiesTestCase::class, mappedBy="testItems")
     */
    private $list;
}
