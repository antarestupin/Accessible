<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessiblePropertiesTrait;
use Accessible\Annotations\Access;
use Accessible\Annotations\DisableConstraintsValidation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @DisableConstraintsValidation
 */
class DisableValidationTestCase
{
    use AccessiblePropertiesTrait;

    /**
     * @Access({Access::GET, Access::SET})
     * @Assert\Type("string")
     * @Assert\Length(min=3)
     */
    private $foo = "foo";
}
