<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessiblePropertiesTrait;
use Accessible\Annotation\Access;

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

    /**
     * @Access({Access::HAS})
     */
    private $baz = "baz";

    private $notAccessibleProperty;
}
