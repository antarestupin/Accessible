<?php

namespace Accessible\Tests;

use Accessible\Configuration;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Validation;
use Doctrine\Common\Cache\ArrayCache;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testAnnotationReaderIsInitialized()
    {
        $reader = Configuration::getAnnotationReader();
        $this->assertNotEquals(null, $reader);
    }

    public function testAnnotationReaderCanBeModified()
    {
        $reader = new AnnotationReader();
        Configuration::setAnnotationReader($reader);
        $returnedReader = Configuration::getAnnotationReader();
        $this->assertEquals($reader, $returnedReader);
    }

    public function testConstraintsValidatorIsInitialized()
    {
        $validator = Configuration::getConstraintsValidator();
        $this->assertNotEquals(null, $validator);
    }

    public function testConstraintsValidatorCanBeModified()
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
        Configuration::setConstraintsValidator($validator);
        $returnedValidator = Configuration::getConstraintsValidator();
        $this->assertEquals($validator, $returnedValidator);
    }

    public function testInitializeValuesValidationCanBeModified()
    {
        Configuration::setInitializeValuesValidationEnabled(false);
        $this->assertEquals(false, Configuration::isInitializeValuesValidationEnabled());
        Configuration::setInitializeValuesValidationEnabled(true);
    }

    public function testCacheDriverCanBeModified()
    {
        $cacheDriver = Configuration::getCacheDriver();
        $newCacheDriver = new ArrayCache();
        Configuration::setCacheDriver($newCacheDriver);
        $this->assertEquals($newCacheDriver, Configuration::getCacheDriver());
        Configuration::setCacheDriver($cacheDriver);
    }

    public function testArrayCacheDriverCanBeModified()
    {
        $arrayCache = Configuration::getArrayCache();
        $newCacheDriver = new ArrayCache();
        Configuration::setArrayCache($newCacheDriver);
        $this->assertEquals($newCacheDriver, Configuration::getArrayCache());
        Configuration::setArrayCache($arrayCache);
    }
}
