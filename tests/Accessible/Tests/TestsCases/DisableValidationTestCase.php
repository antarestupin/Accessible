<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AutomatedBehaviorTrait;
use Accessible\Annotation\Access;
use Accessible\Annotation as Behavior;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Behavior\DisableConstraintsValidation
 */
class DisableValidationTestCase
{
    use AutomatedBehaviorTrait;

    /**
     * @Access({Access::GET, Access::SET})
     * @Assert\Type("string")
     * @Assert\Length(min=3)
     */
    private $foo = "foo";
}
