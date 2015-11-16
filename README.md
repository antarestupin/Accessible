# Accessible

[![Build Status](https://travis-ci.org/antares993/Accessible.svg?branch=master)](https://travis-ci.org/antares993/Accessible)
[![License](https://poser.pugx.org/antares/accessible/license)](https://packagist.org/packages/antares/accessible)

Accessible is a PHP library that allows you to define your class' getters, setters and constructor in an elegant and powerful way using docblock annotations.

Quick example:

```php
/**
 * @Construct({"ipAddress"})
 */
class Server
{
  use AccessiblePropertiesTrait;
  use AutoConstructTrait;

  /**
   * @Access({Access::GET, Access::SET})
   * @Assert\Ip
   */
  private $ipAddress;

  /**
   * @Access({Access::GET})
   * @InitializeObject(ArrayCollection::class)
   */
  private $collection;
}

$server = new Server("192.30.252.128");

$server->getIpAdress();   // 192.30.252.128
$server->getCollection(); // Instance of ArrayCollection

$server->setIpAdress("127.0.0.1");
$ip = $server->getIpAdress(); // 127.0.0.1

$server->setIpAddress("foo"); // Throws \InvalidArgumentException
```

## Install

You can add this library as a dependency using composer this way:

```php
composer require antares/accessible dev-master
```

This library uses the Doctrine annotations library, so if it is not already done you must register the Composer loader in the annotation registry:

```php
$loader = require __DIR__ . '/vendor/autoload.php';
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
```

You may want to change the default configuration, to do this see the [Configuration dedicated page](https://github.com/antares993/Accessible/tree/master/doc/configuration.md).

## Documentation

- [How to define properties access](https://github.com/antares993/Accessible/tree/master/doc/accessible.md)
- [How to define the class constructor](https://github.com/antares993/Accessible/tree/master/doc/auto-construct.md)
- [How to enable / disable the constraints validation](https://github.com/antares993/Accessible/tree/master/doc/constraints-validation.md)
- [How to modify the default configuration](https://github.com/antares993/Accessible/tree/master/doc/configuration.md)

## Compatibility

This library is compatible with PHP 5.4+, PHP 7 and HHVM.
