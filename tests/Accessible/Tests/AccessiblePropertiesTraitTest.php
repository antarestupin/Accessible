<?php

namespace Accessible\Tests;

class AccessiblePropertiesTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testSetMethodCanBeCalled()
    {
        $testCase = new TestsCases\AccessiblePropertiesTestCase();
        $this->assertEquals($testCase, $testCase->setFoo("bar"));
        $this->assertEquals("bar", $testCase->getFoo());
    }

    public function testGetMethodCanBeCalled()
    {
        $testCase = new TestsCases\AccessiblePropertiesTestCase();
        $this->assertEquals("foo", $testCase->getFoo());
    }

    public function testIsCaseCanBeCalled()
    {
        $testCase = new TestsCases\AccessiblePropertiesTestCase();
        $this->assertEquals("bar", $testCase->isBar());
    }

    public function testChildClassCanCallParentMethods()
    {
        $testCase = new TestsCases\AccessiblePropertiesChildTestCase();
        $this->assertEquals("foo", $testCase->getFoo());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testUnavailableMethodCallThrowsAnException()
    {
        $testCase = new TestsCases\AccessiblePropertiesTestCase();
        $testCase->getNotAccessibleProperty();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testCallGetterToInexistentPropertyThrowsAnException()
    {
        $testCase = new TestsCases\AccessiblePropertiesTestCase();
        $testCase->getInexistentProperty();
    }

    public function testConstraintsValidationAllowCorrectValues()
    {
        $testCase = new TestsCases\ConstraintsTestCase();
        $testCase->setFoo("bar");
        $this->assertEquals("bar", $testCase->getFoo());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstraintsValidationDisallowWrongValues()
    {
        $testCase = new TestsCases\ConstraintsTestCase();
        $testCase->setFoo("a");
    }

    public function testDisabledConstraintsValidationAllowWrongValues()
    {
        $testCase = new TestsCases\DisableValidationTestCase();
        $testCase->setFoo("a");
        $this->assertEquals("a", $testCase->getFoo());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstraintsValidationCanBeEnabledManually()
    {
        $testCase = new TestsCases\EnableValidationTestCase();
        $testCase->setFoo("a");
    }
}
