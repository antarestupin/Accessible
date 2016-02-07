# Accessible

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d0791b98-cd96-453a-bf89-39ddcc672c98/mini.png)](https://insight.sensiolabs.com/projects/d0791b98-cd96-453a-bf89-39ddcc672c98)
[![Build Status](https://travis-ci.org/antares993/Accessible.svg?branch=master)](https://travis-ci.org/antares993/Accessible)
[![Code Coverage](https://scrutinizer-ci.com/g/antares993/Accessible/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/antares993/Accessible/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/antares993/Accessible/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/antares993/Accessible/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/antares/accessible/v/stable)](https://packagist.org/packages/antares/accessible)
[![License](https://poser.pugx.org/antares/accessible/license)](https://packagist.org/packages/antares/accessible)


Accessible is a PHP library that allows you to define your class behavior in an elegant and powerful way using docblock annotations.

This way, you can define your class' getters, setters and constructor, and automate collections and association management.

Quick example:

```php
/**
 * @Construct({"ipAddress"})
 */
class Server
{
  use AutomatedBehaviorTrait;

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

If you want to use this library in your Symfony project, take a look at [AccessibleBundle](https://github.com/antares993/AccessibleBundle).

You can add this library as a dependency using composer this way:

```
composer require antares/accessible
```

This library uses the Doctrine annotations library, so if it is not already done you must register the Composer loader in the annotation registry:

```php
$loader = require __DIR__ . '/vendor/autoload.php';
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
```

You may want to change the default configuration, to do this see the [Configuration dedicated page](https://github.com/antares993/Accessible/tree/master/doc/configuration.md).

## Documentation

- [How to define getters and setters](https://github.com/antares993/Accessible/tree/master/doc/accessible.md)
- [How to define the class constructor](https://github.com/antares993/Accessible/tree/master/doc/auto-construct.md)
- [How to enable / disable the constraints validation](https://github.com/antares993/Accessible/tree/master/doc/constraints-validation.md)
- [How to manage collections](https://github.com/antares993/Accessible/tree/master/doc/collections.md)
- [How to manage associations](https://github.com/antares993/Accessible/tree/master/doc/associations.md)
- [How to modify the default configuration](https://github.com/antares993/Accessible/tree/master/doc/configuration.md)

## Compatibility

This library is compatible with PHP 5.4+, PHP 7 and HHVM.
