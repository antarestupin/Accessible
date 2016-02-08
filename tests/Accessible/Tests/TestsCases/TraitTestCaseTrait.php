<?php

namespace Accessible\Tests\TestsCases;

use Accessible\Annotation\Access;

trait TraitTestCaseTrait
{
    /**
     * @Access({Access::GET, Access::SET})
     */
    private $traitProperty;
}
