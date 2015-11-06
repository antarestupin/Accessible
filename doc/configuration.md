## Configuration

### Use a custom annotations reader

The default annotations reader used by this library does only cache the annotations in memory, so it will parse your class files each time a request runs. This is quite slow, and you should use a reader that caches the result between requests. With the following piece of code, the result will be cached in a file.

```php
Accessible\Configuration::setAnnotationReader(
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
Accessible\Configuration::setConstraintsValidator(
    Validation::createValidatorBuilder()
        ->enableAnnotationMapping()
        ->getValidator()
);
```
