<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessibleTrait;
use Accessible\Annotations\Access;
use Accessible\Annotations\DisableConstraintsValidation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @DisableConstraintsValidation
 */
class DisableValidationTestCase
{
    use AccessibleTrait;

    /**
     * @Access({Access::GET, Access::SET})
     * @Assert\Type("string")
     * @Assert\Length(min=3)
     */
    private $foo = "foo";
}
