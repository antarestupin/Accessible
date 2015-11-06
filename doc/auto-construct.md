## How to define the class constructor

Sometimes your classes `__construct` simply consist in taking some arguments and apply them directly as the values of the class' properties; eventually you could also give a fixed initial value to some properties. In these cases you may find useful the `AutoConstructTrait` trait, which will make your class more concise and readable.

### The `@Construct` annotation

The `@Construct` annotation defines which properties will be initialized by the constructor. Take this example:

```php
/**
 * @Construct({"bar", "baz"})
 */
class Foo
{
  use Accessible\AutoConstructTrait;
  use Accessible\AccessiblePropertiesTrait;

  /**
   * @Access({Access::GET})
   */
  private $bar;

  /**
   * @Access({Access::GET})
   */
  private $baz;
}
```

If you want to create an instance of `Foo`, you will do it like this:

```php
$foo = new Foo(42, "sandwich");
$foo->getBar(); // 42
$foo->getBaz(); // "sandwich"
```

The object will be initialized with `$bar` having the value `42` and `$bar` having the value `"sandwich"`. Simple.

If constraints on properties are present, the value you give to the constructor will be checked.

```php
class Foo
{
  // ...

  /**
   * @Access({Access::GET})
   * @Assert("string")
   */
  private $bar;

  // ...
}

$foo = new Foo(42, "sandwich"); // Throws an \InvalidArgumentException as 42 is not a string
```

When using `AutoConstructTrait`, if no `@Construct` has been added, the constructor will be the equivalent of `public function __construct() {}`.

### The `@Initialize` and `@InitializeObject` annotations

For some properties, you may want to fix a value when the object is instanciated. The `@Initialize` and `@InitializeObject` annotations are useful in this situation.

Simply add these annotations to the concerned properties. Let's begin with `@Initialize`:

```php
use Accessible\Annotation\Initialize;

class Foo
{
  use Accessible\AutoConstructTrait;

  /**
   * @Initialize("baz")
   */
  private $bar;
}

$foo = new Foo();
$foo->getBar(); // "baz"
```

The advantage of using `@Initialize` over the native way to set the default value of the property is that the constructor will check that the given value respects the property's constraints.

```php
class Foo
{
  use Accessible\AutoConstructTrait;

  /**
   * @Assert\Type("string")
   */
  private $bar = true; // The value of this property will not be checked

  /**
   * @Assert\Type("string")
   * @Initialize(true)
   */
  private $baz; // A LogicException will be thrown
}
```

For the properties which initial value is an object, if this object has to be instancied with a simple `new Something()` without argument, you can use the `@InitializeObject` annotation.

```php
use Doctrine\Common\Collections\ArrayCollection;

class Foo
{
  use Accessible\AutoConstructTrait;

  /**
   * @InitializeObject(ArrayCollection::class)
   */
  private $bar;
}
```

A new instance of `ArrayCollection` will be set to `$bar` when instanciating a `Foo` object.
