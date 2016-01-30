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

    public function testListAddMethodCanBeCalled()
    {
        $testCase = new TestsCases\AccessiblePropertiesTestCase();
        $testCase->addListItem("item");
        $listItems = $testCase->getListItems();
        $this->assertEquals("item", $listItems[0]);
    }

    public function testListRemoveMethodCanBeCalled()
    {
        $testCase = new TestsCases\AccessiblePropertiesTestCase();
        $testCase->addListItem("item1");
        $testCase->addListItem("item2");
        $testCase->removeListItem("item1");
        $listItems = $testCase->getListItems();
        $this->assertEquals(1, count($listItems));
    }

    public function testMapRemoveMethodCanBeCalled()
    {
        $testCase = new TestsCases\AccessiblePropertiesTestCase();
        $testCase->addMapItem("a", "item1");
        $testCase->addMapItem("b", "item2");
        $testCase->removeMapItem("a");
        $listItems = $testCase->getMapItems();
        $this->assertEquals(1, count($listItems));
    }

    public function testSetRemoveMethodCanBeCalled()
    {
        $testCase = new TestsCases\AccessiblePropertiesTestCase();
        $testCase->addSetItem("item1");
        $testCase->addSetItem("item2");
        $testCase->addSetItem("item2");
        $testCase->removeSetItem("item1");
        $listItems = $testCase->getSetItems();
        $this->assertEquals(1, count($listItems));
    }

    public function testListRemoveMethodCanBeCalledOnTraversableObjects()
    {
        $testCase = new TestsCases\AutoConstructTestCase("ok", true);
        $testCase->addListItem("item1");
        $testCase->addListItem("item2");
        $testCase->removeListItem("item1");
        $listItems = $testCase->getListItems();
        $this->assertEquals(1, count($listItems));
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
