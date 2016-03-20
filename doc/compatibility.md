## Compatibility issues (and how to solve them)

### Symfony's PropertyAccess component

By default, the PropertyAccess component does not work with `__call`. The accessor must be configured to accept it. You can do it this way.

```php
$accessor = PropertyAccess::createPropertyAccessorBuilder()
    ->enableMagicCall()
    ->getPropertyAccessor();
```

In a Symfony project, just add the following lines in the configuration:

```php
# app/config/config.yml
framework:
    property_access:
        magic_call: true
```

### Twig

Although Twig allows the use of `__call`, its use of the magic method is different from the one in PropertyAccess. For example, for `{{ foo.bar }}`, if there is a `__call` method, Twig will call `$foo->bar()` while PropertyAccess will call `$foo->getBar()`.

To deal with this logic, you can use `Access::CALL` to give an access to the property through `$foo->bar()`.
