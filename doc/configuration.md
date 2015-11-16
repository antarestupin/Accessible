## Configuration

### Use a custom annotations reader

The default annotations reader used by this library does only cache the annotations in memory, so it will parse your class files each time a request runs. This is quite slow, and you should use a reader that caches the result between requests. With the following piece of code, the result will be cached in the memory plus the filesystem.

```php
$cache = new Doctrine\Common\Cache\ChainCache([
    new Doctrine\Common\Cache\ArrayCache(),
    new Doctrine\Common\Cache\FilesystemCache("cache/")
]);

Accessible\Configuration::setAnnotationReader(
    new Doctrine\Common\Annotations\CachedReader(
        new Doctrine\Common\Annotations\AnnotationReader(),
        $cache,
        $debug = false
    )
);
```

Note that the `FilesystemCache` cache is not optimal, and if you can, you should better use a faster cache. Here is an example with `ApcCache`:

```php
$cache = new Doctrine\Common\Cache\ChainCache([
    new Doctrine\Common\Cache\ArrayCache(),
    new Doctrine\Common\Cache\ApcCache()
]);

// ...
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

### Use a custom cache driver

You can use a cache in order to avoid the use of the annotation parser whenever possible. This will improve the performance.

```php
Accessible\Configuration::setCacheDriver(
    new Doctrine\Common\Cache\ApcCache()
);
```
