<?php

namespace Accessible\Tests;

class AccessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \BadMethodCallException
     */
    public function testUnavailableMethodCallThrowsAnException()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->getNotAccessibleProperty();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testCallGetterToInexistentPropertyThrowsAnException()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->getInexistentProperty();
    }

    public function testGetAndSetMethodsCanBeCalled()
    {
        $testCase = new TestCases\BaseTestCase();
        $this->assertEquals($testCase, $testCase->setAccessibleProperty("foo"));
        $this->assertEquals("foo", $testCase->getAccessibleProperty());
    }

    public function testIsMethodCanBeCalled()
    {
        $testCase = new TestCases\BaseTestCase();
        $testCase->setBooleanProperty(true);
        $this->assertEquals(true, $testCase->getBooleanProperty());
    }
}
