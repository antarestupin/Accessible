<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessiblePropertiesTrait;
use Accessible\Annotation\Access;
use Symfony\Component\Validator\Constraints as Assert;

class ConstraintsTestCase
{
    use AccessiblePropertiesTrait;

    /**
     * @Access({Access::GET, Access::SET})
     * @Assert\Type("string")
     * @Assert\Length(min=3)
     */
    private $foo = "foo";
}
