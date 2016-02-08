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
        $testCase->bla();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testUnavailablePropertyGetterCallThrowsAnException()
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

    /**
     * @expectedException \BadMethodCallException
     */
    public function testCallGetterWithTheWrongNumberOfArgumentsThrowsAnException()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->getAccessibleProperty("a");
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testCallSetterWithTheWrongNumberOfArgumentsThrowsAnException()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->setAccessibleProperty("a", "b");
    }

    public function testGetAndSetMethodsCanBeCalled()
    {
        $testCase = new TestsCases\BaseTestCase();
        $this->assertEquals($testCase, $testCase->setAccessibleProperty("foo"));
        $this->assertEquals("foo", $testCase->getAccessibleProperty());
    }

    public function testIsMethodCanBeCalled()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->setBooleanProperty(true);
        $this->assertEquals(true, $testCase->isBooleanProperty());
    }

    public function testTraitsAreNotForgotten()
    {
        $testCase = new TestsCases\TraitTestCase();
        $this->assertEquals($testCase, $testCase->setTraitProperty("foo"));
        $this->assertEquals("foo", $testCase->getTraitProperty());
    }
}
