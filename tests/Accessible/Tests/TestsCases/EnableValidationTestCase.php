<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessiblePropertiesTrait;
use Accessible\Annotations\Access;
use Accessible\Annotations\EnableConstraintsValidation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @EnableConstraintsValidation
 */
class EnableValidationTestCase extends DisableValidationTestCase
{

}
