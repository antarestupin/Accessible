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

    public function testListRemoveMethodCanBeCalledOnInvertedProperty()
    {
        $testCase = new TestsCases\AccessiblePropertiesTestCase();
        $toRemove = new \Accessible\Tests\TestsCases\MappedTestCase();
        $testCase->addTestItem($toRemove);
        $this->assertEquals($testCase, $toRemove->getList());

        $testCase->addTestItem(new \Accessible\Tests\TestsCases\MappedTestCase());
        $testCase->removeTestItem($toRemove);
        $this->assertEquals(null, $toRemove->getList());

        $this->assertEquals(1, count($testCase->getTestItems()));
    }

    public function testListRemoveMethodCanBeCalledOnDoublyMappedProperties()
    {
        $testCase = new TestsCases\AccessiblePropertiesTestCase();
        $toRemove = new \Accessible\Tests\TestsCases\MappedTestCase();
        $testCase->addCourse($toRemove);
        $this->assertEquals($testCase, $toRemove->getStudents()[0]);

        $testCase->addCourse(new \Accessible\Tests\TestsCases\MappedTestCase());
        $testCase->removeCourse($toRemove);
        $this->assertEquals(0, count($toRemove->getStudents()));
        $this->assertEquals(1, count($testCase->getCourses()));
    }

    public function testSetMethodCanBeCalledOnAList()
    {
        $testCase = new TestsCases\AccessiblePropertiesTestCase();
        $testCase->addListItem("a");
        $testCase->setListItems(array("b"));
        $this->assertEquals("b", $testCase->getListItems()[0]);
    }

    public function testSetMethodCanBeCalledOnAnAssociatedCollection()
    {
        $testCase = new TestsCases\AccessiblePropertiesTestCase();
        $toRemove = new \Accessible\Tests\TestsCases\MappedTestCase();
        $testCase->addCourse($toRemove);
        $testCase->addCourse(new \Accessible\Tests\TestsCases\MappedTestCase());
        $testCase->setCourses(array(new \Accessible\Tests\TestsCases\MappedTestCase()));
        $this->assertEquals(1, count($testCase->getCourses()));
    }

    public function testMappedAnnotationWorks()
    {
        $testCase = new \Accessible\Tests\TestsCases\MappedTestCase();
        $mapper = new TestsCases\AccessiblePropertiesTestCase();
        $testCase->setList($mapper);
        $this->assertEquals($mapper, $testCase->getList());
        $this->assertEquals($testCase, $mapper->getTestItems()[0]);

        $testCase->setList(null);
        $this->assertEquals(0, count($mapper->getTestItems()));
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

    public function testConstraintsValidationCanBeDisabled()
    {
        $testCase = new TestsCases\ConstraintsTestCase();
        $testCase->setPropertiesConstraintsValidationDisabled();
        $testCase->setFoo("a");
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
