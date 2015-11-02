<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessibleTrait;
use Accessible\Annotations\Access;

class BasicTestCase
{
    use AccessibleTrait;

    /**
     * @Access({Access::GET, Access::SET})
     */
    private $foo = "bar";
}
