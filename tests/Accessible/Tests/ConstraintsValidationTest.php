<?php

namespace Accessible\Tests;

class ConstraintsValidationTest extends \PHPUnit_Framework_TestCase
{
    public function testConstraintsValidationAllowsCorrectValues()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->setConstrainedProperty(50);
        $this->assertEquals(50, $testCase->getConstrainedProperty());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstraintsValidationDisallowsWrongValues()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->setConstrainedProperty(10);
    }

    public function testConstraintsValidationCanBeDisabledUsingAnnotation()
    {
        $testCase = new TestsCases\DisableValidationTestCase();
        $testCase->setConstrainedProperty(10);
        $this->assertEquals(10, $testCase->getConstrainedProperty());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstraintsValidationCanBeEnabledUsingAnnotation()
    {
        $testCase = new TestsCases\EnableValidationTestCase();
        $testCase->setConstrainedProperty("a");
    }

    public function testConstraintsValidationCanBeDisabledManually()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->setPropertiesConstraintsValidationDisabled();
        $testCase->setConstrainedProperty("a");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstraintsValidationCanBeEnabledManually()
    {
        $testCase = new TestsCases\DisableValidationTestCase();
        $testCase->setPropertiesConstraintsValidationEnabled();
        $testCase->setConstrainedProperty("a");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstraintsValidationCanBeUsedManually()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->testPropertyAssertion(10);
    }
}
