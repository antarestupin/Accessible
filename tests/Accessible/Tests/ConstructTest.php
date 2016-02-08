<?php

namespace Accessible\Tests;

class ConstructTest extends \PHPUnit_Framework_TestCase
{
    public function testInitializeAnnotationWorks()
    {
        $testCase = new TestsCases\BaseTestCase();
        $this->assertEquals(1, $testCase->getInitializedIntProperty());
    }

    public function testConstructAnnotationWorks()
    {
        $testCase = new TestsCases\AutoConstructTestCase(50);
        $this->assertEquals(50, $testCase->getConstrainedProperty());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructAnnotationAssertsInitializationValues()
    {
        $testCase = new TestsCases\AutoConstructTestCase('foo');
    }

    public function testInitializationCanBeDoneManually()
    {
        $testCase = new TestsCases\ManualInitializationTestCase();
        $this->assertEquals(50, $testCase->getConstrainedProperty());
    }

    public function testInitializedObjectsDifferAtEachInitialization()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->getInitializedObjectProperty()->add("foo");
        $testCase2 = new TestsCases\BaseTestCase();
        $this->assertEquals(0, count($testCase2->getInitializedObjectProperty()));
    }

    public function testInitializedArraysDifferAtEachInitialization()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->addListItem("foo");
        $testCase2 = new TestsCases\BaseTestCase();
        $this->assertEquals(0, count($testCase2->getListItems()));
    }
}
