<?php

namespace PhpUnitEntityTester\Tests;

use PhpUnitEntityTester\AccessorTester;
use PhpUnitEntityTester\Fixtures\Entity\Entity;

class AccessorTesterTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $entity = new Entity(); 

        $nameTester = new AccessorTester($entity, 'name');
        $nameTester->test('foo')
            ->fluent(false)
            ->setterMethod('setNameNotFluent')
            ->test('bar')
            ;
    }

    public function testNotFluentAndSetterMethod()
    {
        $entity = new Entity(); 

        $nameTester = new AccessorTester($entity, 'name');
        $nameTester->fluent(false)
            ->setterMethod('setNameNotFluent')
            ->test('foo')
            ;
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

