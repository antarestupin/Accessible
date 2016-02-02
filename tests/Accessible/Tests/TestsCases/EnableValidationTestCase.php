<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessiblePropertiesTrait;
use Accessible\Annotation\Access;
use Accessible\Annotation as Behavior;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Behavior\EnableConstraintsValidation
 */
class EnableValidationTestCase extends DisableValidationTestCase
{

}
