<?php

namespace Accessible\Tests\TestsCases;

use Accessible\Annotation as Behavior;

/**
 * @Behavior\Construct({"constrainedProperty"})
 */
class ManualInitializationTestCase extends BaseTestCase
{
    public function __construct()
    {
        $this->initializeProperties(array(50));
    }
}
