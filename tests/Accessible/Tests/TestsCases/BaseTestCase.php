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
     * @Behavior\Inverted(className=AssociationTestCase::class, invertedBy=invertedOneToOneProperty)
     */
    private $invertedOneToOneProperty;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\List
     * @Behavior\Initialize({})
     * @Behavior\Inverted(className=AssociationTestCase::class, invertedBy=invertedManyToOneProperty)
     */
    private $invertedManyToOnePropertyItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\Inverted(className=AssociationTestCase::class, invertedBy=invertedReversedOneToOneProperty)
     */
    private $invertedReversedOneToOneProperty;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\SetBehavior
     * @Behavior\Initialize({})
     * @Behavior\Inverted(className=AssociationTestCase::class, invertedBy=mappedReversedManyToOneProperty)
     */
    private $invertedReversedManyToOnePropertyItems;

    /**
     * @Access({Access::GET, Access::SET})
     * @Behavior\SetBehavior
     * @Behavior\Initialize({})
     * @Behavior\Mapped(className=AssociationTestCase::class, invertedBy=mappedReversedManyToManyProperty)
     */
    private $mappedReversedManyToManyPropertyItems;
}
