<?php

namespace Accessible\Tests;

use Accessible\Configuration;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Validation;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testAnnotationReaderIsInitialized()
    {
        $reader = Configuration::getAnnotationReader();
        $this->assertNotEquals(null, $reader);
    }

    /*public function testAnnotationReaderCanBeModified()
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
    }*/
}
