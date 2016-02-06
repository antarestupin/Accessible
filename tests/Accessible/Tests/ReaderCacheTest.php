<?php

namespace Accessible\Tests;

use \Accessible\Reader\AccessReader;
use \Accessible\Reader\Reader;

class ReaderCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testReaderResultIsCached()
    {
        $testCase = new TestsCases\BaseTestCase();
        $propertiesAccessList = AccessReader::getAccessProperties($testCase);

        $reflectionObject = new \ReflectionObject($testCase);
        $cacheId = md5("accessProperties:" . $reflectionObject->getName());

        $this->assertEquals($propertiesAccessList, Reader::getFromCache($cacheId));
    }
}
