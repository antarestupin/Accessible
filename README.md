# Accessible

Accessible is a PHP library that allows you to define your class' getters and setters with docblock annotations.

## Download / Install

This library is using Doctrine annotations library, so if it is not already done you must register the Composer loader in the annotation registry:

```php
$loader = require __DIR__ . '/vendor/autoload.php';
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
```

## How to use

### Basic use

Add the annotation `@Access` in your class' properties as following. Don't forget to add the use of the `Accessible` trait.

```php
use Accessible\Accessible;
use use Accessible\Annotations\Access;

class Foo
{
  use Accessible;

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

- `Access::GET`: This will allow the method `Foo#getBar()` to be called.
- `Access::IS`: This will allow the method `Foo#isBar()` to be called.
- `Access::HAS`: This will allow the method `Foo#getBar()` to be called.
- `Access::SET`: This will allow the method `Foo#setBar($newVal)` to be called.

### Add constraints on properties

Things are getting really interesting when you want to add constraints to your properties. You can use [Symfony's Validator constraints](https://github.com/symfony/Validator) to restrict the values your properties can be set to. A reference of these constraints can be found [here](http://symfony.com/doc/current/reference/constraints.html).

For example, with this class:

```php
use Symfony\Component\Validator\Constraints as Assert;

class Foo
{
  use Accessible;

  /**
   * @Access({Access::GET, Access::SET})
   * @Assert\Type("string")
   * @Assert\Length(min=3)
   */
  private $bar;
}
```

When a setter will be called on the `$bar` property, the new value given to the setter will be checked to satisfy the defined constraints, so if it is not a string with a minimal length of 3, an `\InvalidArgumentException` will be thrown.

```php
$foo = new Foo();
$foo->setBar('baz'); // this will work
$foo->setBar('a'); // this won't, and will throw an \InvalidArgumentException
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

And that's it! In fact, it's better to let `Access::SET` in your annotation as it informs that the property can be modified via a setter, even if you define the setter yourself.

If you do so, you could be interested in validating the value given to your setter inside it. It is possible this way:

```php
class Foo
{
  // ...

  public function setBar($newValue)
  {
    if (!$this->_validatePropertyValue('bar', $newValue)->count()) {
      $this->bar = $newValue;
      return $this;
    } else {
      throw new \InvalidArgumentException("The value passsed to Foo#setBar() is not valid.");
    }
  }
}
```

The method `_validatePropertyValue()` returns a ConstraintViolationList, which `count()` will equal 0 if the value to check is ok with your property constraints.

## Todo

- Add PHPUnit tests
- Add an annotation to deactivate the constraints validation
