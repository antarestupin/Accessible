<?php

namespace Accessible\Tests;

class AccessibleTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testSetMethodCanBeCalled()
    {
        $testCase = new TestsCases\BasicTestCase();
        $this->assertEquals($testCase, $testCase->setFoo("bar"));
        $this->assertEquals("bar", $testCase->getFoo());
    }

    public function testGetMethodCanBeCalled()
    {
        $testCase = new TestsCases\BasicTestCase();
        $this->assertEquals("foo", $testCase->getFoo());
    }

    public function testIsCaseCanBeCalled()
    {
        $testCase = new TestsCases\BasicTestCase();
        $this->assertEquals("bar", $testCase->isBar());
    }

    public function testHasMethodCanBeCalled()
    {
        $testCase = new TestsCases\BasicTestCase();
        $this->assertEquals("baz", $testCase->hasBaz());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testUnavailableMethodCallThrowsAnException()
    {
        $testCase = new TestsCases\BasicTestCase();
        $testCase->getNotAccessibleProperty();
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
}
