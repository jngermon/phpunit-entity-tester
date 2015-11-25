<?php

namespace PhpUnitEntityTester\Tests;

use PhpUnitEntityTester\AccessorTester;
use PhpUnitEntityTester\Fixtures\Entity\Entity;

class AccessorTesterTest extends \PHPUnit_Framework_TestCase
{
    protected $accessorTester;

    public function setup()
    {
        $entity = new Entity(); 

        $this->accessorTester = new AccessorTester($entity, 'name');
    }

    public function testSimple()
    {
        $this->accessorTester->test('foo')
            ->test('bar')
            ;
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage The method 'setNameNotFluent' is not fluent.
     */
    public function testSetFluent()
    {
        $this->accessorTester->setterMethod('setNameNotFluent')
            ->test('foo');
    }

    public function testSetNotFluent()
    {
        $this->accessorTester->fluent(false)
            ->test('foo');
    }

    public function testGetterSpecial()
    {
        $entity = new Entity(); 

        $nameTester = new AccessorTester($entity, 'name');
        $nameTester->getterMethod('getSpecialName')
            ->test('foo', 'fooSpecial')
            ;
    }
}

