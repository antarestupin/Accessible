# Accessible

[![Build Status](https://travis-ci.org/antares993/Accessible.svg?branch=master)](https://travis-ci.org/antares993/Accessible)

Accessible is a PHP library that allows you to define your class' getters and setters with docblock annotations.

## Download / Install

You can add this library as a dependency using composer this way:

```php
composer require antares/accessible
```

This library uses the Doctrine annotations library, so if it is not already done you must register the Composer loader in the annotation registry:

```php
$loader = require __DIR__ . '/vendor/autoload.php';
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
```

## How to use

### Basic use

Add the annotation `@Access` in your class' properties as following. Don't forget to add the use of the `AccessibleTrait` trait.

```php
use Accessible\AccessibleTrait;
use use Accessible\Annotations\Access;

class Foo
{
  use AccessibleTrait;

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
- `Access::HAS`: This will allow the property `$bar` to be accessed through the `Foo#getBar()` method.
- `Access::SET`: This will allow the property `$bar` to be modified through the `Foo#setBar($newVal)` method.

### Add constraints on properties

Things are getting really interesting when you want to add constraints to your properties. You can use [Symfony's Validator constraints](https://github.com/symfony/Validator) to restrict the values your properties can be set to. A reference of these constraints can be found [here](http://symfony.com/doc/current/reference/constraints.html).

For example, with this class:

```php
use Symfony\Component\Validator\Constraints as Assert;

class Foo
{
  use AccessibleTrait;

  /**
   * @Access({Access::GET, Access::SET})
   * @Assert\Type("string")
   * @Assert\Length(min=3)
   */
  private $bar;
}
```

When a setter will be called on the `$bar` property, the new value given to the setter will be checked to satisfy the defined constraints, so if it is not a string with a minimal length of 3, an `\InvalidArgumentException` will be thrown, including in its message the list of constraints not respected by the value.

```php
$foo = new Foo();
$foo->setBar('baz'); // this will work
$foo->setBar('a'); // this won't, and will throw an \InvalidArgumentException with the message "This value is too short. It should have 3 characters or more."
```

### Disable the constraints validation

In some cases, for example if you are using Symfony's forms, you may want to use this library to generate the setters of some classes without constraints validation in order to validate the entire class later. To do this, simply add the `@DisableConstraintsValidation` annotation on the docblock of your class.

```php
use Accessible\Annotations\DisableConstraintsValidation;

/**
 * @DisableConstraintsValidation
 */
class Foo
{
  // ...

  /**
   * @Access({Access::GET, Access::SET})
   * @Assert\Type("string")
   * @Assert\Length(min=3)
   */
  private $bar;
}

$foo = new Foo();
$foo->setBar('a'); // This will work
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
    if ($this->_validatePropertyValue('bar', $newValue)->count()) {
      throw new \InvalidArgumentException("The value passsed to Foo#setBar() is not valid.");
    }
    $this->bar = $newValue;
    return $this;
  }
}
```

The method `_validatePropertyValue()` returns a ConstraintViolationList, which `count()` will equal 0 if the value to check is ok with your property constraints.

### Use a custom annotations reader

The default annotations reader used by this library does only cache the annotations in memory, so it will parse your class files each time a request runs. This is quite slow, and you should use a reader that caches the result between requests. With the following piece of code, the result will be cached in a file.

```php
Accessible\AccessReader::setAnnotationReader(
    new Doctrine\Common\Annotations\FileCacheReader(
        new Doctrine\Common\Annotations\AnnotationReader(),
        "cache/",
        $debug = false
    )
);
```

### Use a custom constraints validator

If you are already using the Symfony validator in your code, you may want this library to use it, as one validator is enough. You can set which validator will be used this way:

```php
AccessReader::setConstraintsValidator(
    Validation::createValidatorBuilder()
        ->enableAnnotationMapping()
        ->getValidator()
);
```

## Todo

- Add PHPUnit tests
