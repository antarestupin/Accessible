<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessibleTrait;
use Accessible\Annotations\Access;
use Symfony\Component\Validator\Constraints as Assert;

class ConstraintsTestCase
{
    use AccessibleTrait;

    /**
     * @Access({Access::GET, Access::SET})
     * @Assert\Type("string")
     * @Assert\Length(min=3)
     */
    private $foo = "foo";
}
