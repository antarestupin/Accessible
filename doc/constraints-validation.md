## How to enable / disable the constraints validation

By default, the constraints validation is enabled, but for some reason, you may want to disable it, or you will need to enable it manually.

### Disable the constraints validation

In some cases, for example if you are using Symfony's forms, you may want to use this library to generate the setters of some classes without constraints validation in order to validate the entire class later. To do this, simply add the `@DisableConstraintsValidation` annotation on the docblock of your class.

```php
use Accessible\Annotation\DisableConstraintsValidation;

/**
 * @DisableConstraintsValidation
 */
class Foo
{
  // ...

  /**
   * Given the following constraints, $bar should only be set to a string of 3 characters or more.
   *
   * @Access({Access::GET, Access::SET})
   * @Assert\Type("string")
   * @Assert\Length(min=3)
   */
  private $bar;
}

$foo = new Foo();
$foo->setBar('bar'); // This is ok
$foo->setBar('a'); // This is not ok, but it will work anyway
```

This example's `Foo` class and every class extending it will have the constraints validation disabled.

### Enable the constraints validation

Now let's say you want to create a class that extends `Foo` and uses constraints validation. To override `Foo` behavior, add the `@EnableConstraintsValidation` annotation to the child class.

```php
use Accessible\Annotation\EnableConstraintsValidation;

/**
 * @EnableConstraintsValidation
 */
class Bar extends Foo
{

}

$bar = new Bar();
$bar->setBar('a'); // This will throw an \InvalidArgumentException
```
