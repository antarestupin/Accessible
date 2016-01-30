<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessiblePropertiesTrait;
use Accessible\Annotation\Access;
use Accessible\Annotation\ListBehavior;
use Accessible\Annotation\MapBehavior;
use Accessible\Annotation\SetBehavior;

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
     * @Access({Access::GET})
     * @ListBehavior
     */
    private $listItems;

    /**
     * @Access({Access::GET})
     * @MapBehavior
     */
    private $mapItems;

    /**
     * @Access({Access::GET})
     * @SetBehavior
     */
    private $setItems;

    public function __construct()
    {
        $this->listItems = array();
        $this->mapItems = array();
        $this->setItems = array();
    }
}
