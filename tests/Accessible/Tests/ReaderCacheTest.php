<?php

namespace Accessible\Tests;

use \Accessible\Reader\AccessReader;
use \Accessible\Reader\Reader;

class ReaderCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testReaderResultIsCached()
    {
        $testCase = new TestsCases\BaseTestCase();
        $classInformation = Reader::getClassInformation($testCase);

        $reflectionObject = new \ReflectionObject($testCase);
        $cacheId = md5("classInformation:" . $reflectionObject->getName());

        $this->assertEquals($classInformation, Reader::getFromCache($cacheId));
    }
}
