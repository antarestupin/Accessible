<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessiblePropertiesTrait;
use Accessible\AutoConstructTrait;
use Accessible\Annotations\Access;
use Accessible\Annotations\Construct;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Construct({"foo", "bar"})
 */
class AutoConstructTestCase
{
    use AutoConstructTrait;
    use AccessiblePropertiesTrait;

    /**
     * @Access({Access::GET})
     */
    private $foo;

    /**
     * @Access({Access::GET})
     * @Assert\Type("bool")
     */
    private $bar;
}
