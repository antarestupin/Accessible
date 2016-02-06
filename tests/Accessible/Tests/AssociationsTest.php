<?php

namespace Accessible\Tests;

class AssociationsTest extends \PHPUnit_Framework_TestCase
{
    public function testOneToOneAssociationWorks()
    {
        $testCase = new TestsCases\BaseTestCase();
        $associatedTestCase = new TestsCases\AssociationTestCase();

        $testCase->setInvertedOneToOneProperty($associatedTestCase);
        $this->assertEquals($testCase, $associatedTestCase->getInvertedOneToOneProperty());

        $testCase->setInvertedOneToOneProperty(new TestsCases\AssociationTestCase());
        $this->assertEquals(null, $associatedTestCase->getInvertedOneToOneProperty());
    }

    public function testReversedOneToOneAssociationWorks()
    {
        $testCase = new TestsCases\BaseTestCase();
        $associatedTestCase = new TestsCases\AssociationTestCase();

        $testCase->setInvertedReversedOneToOneProperty($associatedTestCase);
        $this->assertEquals($testCase, $associatedTestCase->getInvertedReversedOneToOneProperty());

        $testCase->setInvertedReversedOneToOneProperty(new TestsCases\AssociationTestCase());
        $this->assertEquals(null, $associatedTestCase->getInvertedReversedOneToOneProperty());

        $testCase->setInvertedReversedOneToOneProperty($associatedTestCase);
        $this->assertEquals($associatedTestCase, $testCase->getInvertedReversedOneToOneProperty());

        $associatedTestCase->setInvertedReversedOneToOneProperty(new TestsCases\AssociationTestCase());
        $this->assertEquals(null, $testCase->getInvertedReversedOneToOneProperty());
    }

    public function testManyToOneAssociationWorks()
    {
        $testCase = new TestsCases\BaseTestCase();
        $associatedTestCase = new TestsCases\AssociationTestCase();
        $testCase->addInvertedManyToOnePropertyItem(new TestsCases\AssociationTestCase());

        $testCase->addInvertedManyToOnePropertyItem($associatedTestCase);
        $this->assertEquals($testCase, $associatedTestCase->getMappedManyToOneProperty());

        $testCase->removeInvertedManyToOnePropertyItem($associatedTestCase);
        $this->assertEquals(null, $associatedTestCase->getMappedManyToOneProperty());
        $this->assertEquals(1, count($testCase->getInvertedManyToOnePropertyItems()));
    }

    public function testReversedManyToOneAssociationWorks()
    {
        $testCase = new TestsCases\BaseTestCase();
        $associatedTestCase = new TestsCases\AssociationTestCase();
        $testCase->addInvertedReversedManyToOnePropertyItem(new TestsCases\AssociationTestCase());

        $testCase->addInvertedReversedManyToOnePropertyItem($associatedTestCase);
        $this->assertEquals($testCase, $associatedTestCase->getMappedReversedManyToOneProperty());

        $testCase->removeInvertedReversedManyToOnePropertyItem($associatedTestCase);
        $this->assertEquals(null, $associatedTestCase->getMappedReversedManyToOneProperty());
        $this->assertEquals(1, count($testCase->getInvertedReversedManyToOnePropertyItems()));

        $associatedTestCase->setMappedReversedManyToOneProperty($testCase);
        $this->assertEquals(2, count($testCase->getInvertedReversedManyToOnePropertyItems()));

        $associatedTestCase->setMappedReversedManyToOneProperty(null);
        $this->assertEquals(1, count($testCase->getInvertedReversedManyToOnePropertyItems()));
    }

    public function testManyToManyAssociationWorks()
    {
        $testCase = new TestsCases\BaseTestCase();
        $associatedTestCase = new TestsCases\AssociationTestCase();

        $testCase->addMappedManyToManyPropertyItem($associatedTestCase);
        $this->assertEquals($testCase, $associatedTestCase->getMappedManyToManyPropertyItems()[0]);

        $testCase->addMappedManyToManyPropertyItem(new TestsCases\AssociationTestCase());
        $testCase->removeMappedManyToManyPropertyItem($associatedTestCase);
        $this->assertEquals(0, count($associatedTestCase->getMappedManyToManyPropertyItems()));
    }

    public function testReversedManyToManyAssociationWorks()
    {
        $testCase = new TestsCases\BaseTestCase();
        $associatedTestCase = new TestsCases\AssociationTestCase();

        $testCase->addMappedReversedManyToManyPropertyItem($associatedTestCase);
        $this->assertEquals($testCase, $associatedTestCase->getMappedReversedManyToManyPropertyItems()[0]);

        $testCase->addMappedReversedManyToManyPropertyItem(new TestsCases\AssociationTestCase());
        $testCase->removeMappedReversedManyToManyPropertyItem($associatedTestCase);
        $this->assertEquals(0, count($associatedTestCase->getMappedReversedManyToManyPropertyItems()));

        $associatedTestCase->addMappedReversedManyToManyPropertyItem($testCase);
        $this->assertEquals(2, count($testCase->getMappedReversedManyToManyPropertyItems()));

        $associatedTestCase->removeMappedReversedManyToManyPropertyItem($testCase);
        $this->assertEquals(1, count($testCase->getMappedReversedManyToManyPropertyItems()));
    }

    public function testAssociationUpdateCanBeDoneManually()
    {
        $testCase = new TestsCases\BaseTestCase();
        $associatedTestCase = new TestsCases\AssociationTestCase();
        $testCase->testPropertyAssociationUpdate($associatedTestCase);
        $this->assertEquals($testCase, $associatedTestCase->getInvertedOneToOneProperty());
    }
}
