<?php

namespace Accessible\Tests\TestsCases;

use Accessible\Tests\SampleClasses\Foo;
use Accessible\AccessiblePropertiesTrait;
use Accessible\AutoConstructTrait;
use Accessible\Annotations\Access;
use Accessible\Annotations\Construct;
use Accessible\Annotations\Initialize;
use Accessible\Annotations\InitializeObject;
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

    /**
     * @Access({Access::GET})
     * @Initialize("baz")
     */
    private $baz;

    /**
     * @Access({Access::GET})
     * @InitializeObject(Foo::class)
     */
    private $object;
}
