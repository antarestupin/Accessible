<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AutomatedBehaviorTrait;
use Accessible\Annotation\Access;
use Accessible\Annotation as Behavior;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Behavior\Construct({"foo", "bar"})
 */
class AutoConstructTestCase
{
    use AutomatedBehaviorTrait;

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
     * @Behavior\Initialize("baz")
     */
    private $baz;

    /**
     * @Access({Access::GET})
     * @Behavior\InitializeObject(AccessiblePropertiesTestCase::class)
     */
    private $object;

    /**
     * @Access({Access::GET})
     * @Behavior\ListBehavior
     * @Behavior\InitializeObject(ArrayCollection::class)
     */
    private $listItems;
}
