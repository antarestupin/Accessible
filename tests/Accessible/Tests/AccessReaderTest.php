<?php

namespace Accessible\Tests;

use Accessible\AccessReader;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Validation;

class AccessReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testAnnotationReaderIsInitialized()
    {
        $reader = AccessReader::getAnnotationReader();
        $this->assertNotEquals(null, $reader);
    }

    public function testAnnotationReaderCanBeModified()
    {
        $reader = new AnnotationReader();
        AccessReader::setAnnotationReader($reader);
        $returnedReader = AccessReader::getAnnotationReader();
        $this->assertEquals($reader, $returnedReader);
    }

    public function testConstraintsValidatorIsInitialized()
    {
        $validator = AccessReader::getConstraintsValidator();
        $this->assertNotEquals(null, $validator);
    }

    public function testConstraintsValidatorCanBeModified()
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
        AccessReader::setConstraintsValidator($validator);
        $returnedValidator = AccessReader::getConstraintsValidator();
        $this->assertEquals($validator, $returnedValidator);
    }
}
