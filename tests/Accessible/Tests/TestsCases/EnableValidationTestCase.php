<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AccessiblePropertiesTrait;
use Accessible\Annotation\Access;
use Accessible\Annotation\EnableConstraintsValidation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @EnableConstraintsValidation
 */
class EnableValidationTestCase extends DisableValidationTestCase
{

}
