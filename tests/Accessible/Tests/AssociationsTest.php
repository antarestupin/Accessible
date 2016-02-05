<?php

namespace Accessible\Tests;

class AssociationsTest extends \PHPUnit_Framework_TestCase
{
    public function testAddAndRemoveMethodCanBeCalledOnInvertedCollections()
    {
        $testCase = new TestsCases\BaseTestCase();
        $toRemove = new TestsCases\AssociationTestCase();
        $testCase->addInvertedManyToOnePropertyItem($toRemove);
        $this->assertEquals($testCase, $toRemove->getInvertedManyToOneProperty());

        $testCase->addTestItem(new TestsCases\MappedTestCase());
        $testCase->removeInvertedManyToOnePropertyItem($toRemove);
        $this->assertEquals(null, $toRemove->getInvertedManyToOneProperty());

        $this->assertEquals(1, count($testCase->getInvertedManyToOneProperty()));
    }

    public function testListRemoveMethodCanBeCalledOnDoublyMappedProperties()
    {
        $testCase = new TestsCases\BaseTestCase();
        $toRemove = new TestsCases\AssociationTestCase();
        $testCase->addMappedReversedManyToManyPropertyItem($toRemove);
        $this->assertEquals($testCase, $toRemove->getMappedReversedManyToManyPropertyItems()[0]);

        $testCase->addMappedReversedManyToManyPropertyItem(new TestsCases\MappedTestCase());
        $testCase->removeCourse($toRemove);
        $this->assertEquals(0, count($toRemove->getMappedReversedManyToManyPropertyItems()));
        $this->assertEquals(1, count($testCase->getMappedReversedManyToManyPropertyItems()));
    }
}
