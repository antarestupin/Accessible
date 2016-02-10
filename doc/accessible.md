## How to define getters and setters

### Basic use

Add the annotation `@Access` in your class' properties as following. Don't forget to add the use of the `AutomatedBehaviorTrait` trait.

```php
use Accessible\AutomatedBehaviorTrait;
use Accessible\Annotation\Access;

class Foo
{
  use AutomatedBehaviorTrait;

  /**
   * @Access({Access::GET, Access::SET})
   */
  private $bar;
}
```

This annotation defines a getter and a setter for the `$bar` property. Now you can use `getBar()` and `setBar()` on an instance of Foo.

```php
$foo = new Foo();
$foo->setBar("baz");
$foo->getBar(); // "baz"
```

This is basically as if you defined `getBar()` and `setBar()` like this:

```php
class Foo
{
  // ...

  function getBar() {
    return $this->bar;
  }

  function setBar($bar) {
    $this->bar = $bar;
    return $this;
  }
}
```

### List of methods that can be defined this way

Let's take again the `Foo` class with its `$bar` property. Here are the values you can put in the `@Access` annotation:

- `Access::GET`: This will allow the property `$bar` to be accessed through the `Foo#getBar()` method.
- `Access::IS`: This will allow the property `$bar` to be accessed through the `Foo#isBar()` method.
- `Access::CALL`: This will allow the property `$bar` to be accessed through the `Foo#bar()` method (it can be useful in some specific cases, for example when using Twig).
- `Access::SET`: This will allow the property `$bar` to be modified through the `Foo#setBar($newVal)` method.

### Add constraints on properties

Things are getting really interesting when you want to add constraints to your properties. You can use [Symfony's Validator constraints](https://github.com/symfony/Validator) to restrict the values your properties can be set to. A reference of these constraints can be found [here](http://symfony.com/doc/current/reference/constraints.html).

For example, with this class:

```php
use Symfony\Component\Validator\Constraints as Assert;

class Foo
{
  use AutomatedBehaviorTrait;

  /**
   * @Access({Access::GET, Access::SET})
   * @Assert\NotNull
   * @Assert\Email
   */
  private $email;
}
```

When a setter will be called on the `$email` property, the new value given to the setter will be checked to satisfy the defined constraints, so if it is not an email address, an `\InvalidArgumentException` will be thrown, including in its message a list of the constraints not respected by the value.

```php
$foo = new Foo();
$foo->setEmail('john.doe@example.com'); // this will work
$foo->setEmail('bar'); // this won't, and will throw an \InvalidArgumentException with a message including "This value is not a valid email address."
```

### Add hand-made getters and setters

Let's say you want to define yourself how the property `$bar` should be modified now. Good news, you can do it without even touching the annotation! Just add your setter like this:

```php
class Foo
{
  // ...

  public function setBar($bar) {
    $this->bar = $bar;
    return $this;
  }
}
```

And that's it! After that, you can let the `Access::SET` or remove it. In fact, it's better to let `Access::SET` in your annotation as it informs that the property can be modified via a setter, even if you define the setter yourself.

If you do so, you could be interested in validating the value given to your setter inside it. It is possible this way:

```php
class Foo
{
  // ...

  public function setBar($newValue)
  {
    $this->assertPropertyValue('bar', $newValue);
    $this->bar = $newValue;
    return $this;
  }
}
```

The method `assertPropertyValue()` will throw an `\InvalidArgumentException` if `$newValue` violates the property constraints.
