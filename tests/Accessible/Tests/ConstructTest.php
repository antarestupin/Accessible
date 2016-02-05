<?php

namespace Accessible\Tests;

class ConstructTest extends \PHPUnit_Framework_TestCase
{
    public function testInitializeAnnotationWorks()
    {
        $testCase = new TestCases\BaseTestCase();
        $this->assertEquals(1, $testCase->getInitializedIntProperty());
    }
}
