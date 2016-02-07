## How to enable / disable the constraints validation

By default, the constraints validation is enabled, but for some reason, you may want to disable it, or you will need to enable it manually. For example if you are using Symfony's forms, you may want to disable constraints validation in order to validate the entire class later.

There are two ways to enable or disable the validation: using annotations and using methods provided by this library.

### Using annotations

These annotations are useful when you are sure that constraints validation will always or never be used on a class.

#### Disable the constraints validation on a class

To do this, simply add the `@DisableConstraintsValidation` annotation on the docblock of your class.

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

#### Enable the constraints validation on a class

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

### Using the provided methods

This library provides 3 methods related to constraints validation:

- `isPropertiesConstraintsValidationEnabled()`: This method indicates wether the validation is enabled or not.
- `setPropertiesConstraintsValidationEnabled($enabled = true)`: This method enables the validation (or disables it, if `$enabled` is set to `false`).
- `setPropertiesConstraintsValidationDisabled($disabled = true)`: This method disables the validation (or enables it, if `$disabled` is set to `false`).

Note that `setPropertiesConstraintsValidationEnabled()` and `setPropertiesConstraintsValidationDisabled()` override the behavior you may define using `@EnableConstraintsValidation` and `@DisableConstraintsValidation`.

These methods are typically useful in cases such as in the Symfony's forms example. Here is an example of how to use these methods with forms.

The entity:

```php
class User
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

The controller:

```php
public function newAction(Request $request)
{
  $user = new User();
  $user->setPropertiesConstraintsValidationDisabled(); // disable the validation for this object

  $form = $this->createFormBuilder($user)
    ->add('email', EmailType::class)
    ->add('save', SubmitType::class, array('label' => 'Create User'))
    ->getForm();

  $form->handleRequest($request);

  if ($form->isSubmitted() && $form->isValid()) {
    // ... perform some action, such as saving the user to the database

    return $this->redirectToRoute('user_success');
  }

  return $this->render('default/new.html.twig', array(
    'form' => $form->createView(),
  ));
}
```
