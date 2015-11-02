<?php

namespace Accessible\Tests;

class AccessibleTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testSetGetCase()
    {
        $testCase = new TestsCases\BasicTestCase();
        $this->assertEquals($testCase, $testCase->setFoo("baz"));
        $this->assertEquals("baz", $testCase->getFoo());
    }
}
