<?php

namespace Accessible\Tests\TestsCases;

use Accessible\AutomatedBehaviorTrait;
use Accessible\Annotation\Access;
use Accessible\Annotation as Behavior;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

class BaseTestCase
{
    use AutomatedBehaviorTrait;

    private $notAccessibleProperty;

    /**
     * @Access({Access::GET, Access::SET})
     */
    private $accessibleProperty;

    /**
     * @Access({Access::IS, Access::SET})
     */
    private $booleanProperty;

    /**
     * @Access({Access::CALL, Access::SET})
     */
    private $callProperty;

    /**
     * @Access({Access::GET, Access::SET})
     * @Assert\Type("integer")
     * @Assert\GreaterThan(42)
     */
    private $constrainedProperty;

    /**
     * @Access({Access::GET, Access::SET})
     * @Assert\Type("integer")
     * @Behavior\Initialize(1)
     */
    private $initializedIntProperty;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\InitializeObject(ArrayCollection::class)
     */
    private $initializedObjectProperty;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\ListBehavior
     * @Behavior\Initialize({})
     * @Assert\Count(max=4)
     */
    private $listItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\SetBehavior
     * @Behavior\Initialize({})
     */
    private $setItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\MapBehavior
     * @Behavior\Initialize({})
     */
    private $mapItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\ListBehavior
     * @Behavior\InitializeObject(ArrayCollection::class)
     */
    private $doctrineCollectionItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\Inverted(className=AssociationTestCase::class, invertedBy="invertedOneToOneProperty")
     */
    private $invertedOneToOneProperty;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\ListBehavior
     * @Behavior\Initialize({})
     * @Behavior\Inverted(className=AssociationTestCase::class, invertedBy="mappedManyToOneProperty")
     */
    private $invertedManyToOnePropertyItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\Inverted(className=AssociationTestCase::class, invertedBy="invertedReversedOneToOneProperty")
     */
    private $invertedReversedOneToOneProperty;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\SetBehavior
     * @Behavior\Initialize({})
     * @Behavior\Inverted(className=AssociationTestCase::class, invertedBy="mappedReversedManyToOneProperty")
     */
    private $invertedReversedManyToOnePropertyItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\SetBehavior
     * @Behavior\Initialize({})
     * @Behavior\Mapped(className=AssociationTestCase::class, mappedBy="mappedManyToManyPropertyItems")
     */
    private $mappedManyToManyPropertyItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\SetBehavior
     * @Behavior\Initialize({})
     * @Behavior\Mapped(className=AssociationTestCase::class, mappedBy="mappedReversedManyToManyPropertyItems")
     */
    private $mappedReversedManyToManyPropertyItems;

    public function testPropertyAssertion($value)
    {
        $this->assertPropertyValue("constrainedProperty", $value);
    }

    public function testPropertyAssociationUpdate($value)
    {
        $this->invertedOneToOneProperty = $value;
        $this->updatePropertyAssociation("invertedOneToOneProperty", array('newValue' => $value));
    }
}
