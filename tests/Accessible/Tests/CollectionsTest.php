<?php

namespace Accessible\Tests;

class CollectionsTest extends \PHPUnit_Framework_TestCase
{
    public function testListAddAndRemoveMethodsCanBeCalled()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->addListItem("item1");
        $testCase->addListItem("item2");
        $testCase->removeListItem("item1");
        $listItems = $testCase->getListItems();
        $this->assertEquals(1, count($listItems));
    }

    public function testMapAddAndRemoveMethodsCanBeCalled()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->addMapItem("a", "item1");
        $testCase->addMapItem("b", "item2");
        $testCase->removeMapItem("a");
        $listItems = $testCase->getMapItems();
        $this->assertEquals(1, count($listItems));
    }

    public function testSetAddAndRemoveMethodsCanBeCalled()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->addSetItem("item1");
        $testCase->addSetItem("item2");
        $testCase->addSetItem("item2");
        $testCase->removeSetItem("item1");
        $listItems = $testCase->getSetItems();
        $this->assertEquals(1, count($listItems));
    }

    public function testListRemoveMethodCanBeCalledOnTraversableObjects()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->addDoctrineCollectionItem("item1");
        $testCase->addDoctrineCollectionItem("item2");
        $testCase->removeDoctrineCollectionItem("item1");
        $this->assertEquals(1, count($testCase->getDoctrineCollectionItems()));
    }

    public function testSetMethodCanBeCalledOnAList()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->addListItem("a");
        $testCase->setListItems(array("b"));
        $this->assertEquals("b", $testCase->getListItems()[0]);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testMapAddMethodCannotAcceptOneArgument()
    {
        $testCase = new TestsCases\BaseTestCase();
        $testCase->addMapItem("item1");
    }
}
