<?php

namespace PhpUnitEntityTester\Tests;

use PhpUnitEntityTester\AccessorCollectionTester;
use PhpUnitEntityTester\Fixtures\Entity\EntityForCollection;

class AccessorCollectionTesterTest extends \PHPUnit_Framework_TestCase
{
    protected $collectionTester;

    public function setup()
    {
        $entity = new EntityForCollection();

        $this->collectionTester = new AccessorCollectionTester($entity, 'tests');
    }

    public function testSimple()
    {
        $this->collectionTester->unique(false)
            ->test('value1', 'value2')
            ;
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage The method 'addTest' doesn't respect unicity.
     */
    public function testUniqueFail()
    {
        $this->collectionTester->unique(true)
            ->test('value1', 'value2')
            ;
    }

    public function testUnique()
    {
        $this->collectionTester->unique(true)
            ->addMethod('addTestUnique')
            ->test('value1', 'value2')
            ;
    }
    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage The method 'addTestUnique' respect unicity for nothing.
     */
    public function testNonUniqueFail()
    {
        $this->collectionTester->unique(false)
            ->addMethod('addTestUnique')
            ->test('value1', 'value2')
            ;
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage The method 'addTestNotFluent' is not fluent.
     */
    public function testAddFluent()
    {
        $this->collectionTester->fluent(true)
            ->addMethod('addTestNotFluent')
            ->testAdd('value1')
            ;
    }
    
    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage The method 'badAddTest' does not add data.
     */
    public function testBadAddMethod()
    {
        $this->collectionTester
            ->addMethod('badAddTest')
            ->testAdd('value1')
            ;
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage The method 'removeTestNotFluent' is not fluent.
     */
    public function testRemoveFluent()
    {
        $this->collectionTester->fluent(true)
            ->removeMethod('removeTestNotFluent')
            ->testAdd('value1')
            ->testRemove('value1')
            ;
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage The method 'badRemoveTest' does not remove data.
     */
    public function testBadRemoveMethod()
    {
        $this->collectionTester
            ->removeMethod('badRemoveTest')
            ->testAdd('value1')
            ->testRemove('value1')
            ;
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage The method 'badRemoveTestReset' does not remove the good number of items.
     */
    public function testBadRemoveMethodReset()
    {
        $this->collectionTester
            ->removeMethod('badRemoveTestReset')
            ->testAdd('value1')
            ->testAdd('value2')
            ->testRemove('value1')
            ;
    }

    public function testGetMethod()
    {
        $this->collectionTester
            ->testGet()
            ;
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage The method 'badGetTests' must not return null.
     */
    public function testBadGetMethod()
    {
        $this->collectionTester
            ->getMethod('badGetTests')
            ->testGet()
            ;
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage The method 'badGetCollectionNotCountable' must return an instance of interface 'Coutable'.
     */
    public function testBadGetMethodNotCountable()
    {
        $this->collectionTester
           ->getMethod('badGetCollectionNotCountable') 
           ->testGet()
           ;
    }
    
    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage The method 'badGetCollectionNotTraversable' must return an instance of interface 'Traversable'.
     */
    public function testBadGetMethodNotTraversable()
    {
        $this->collectionTester
           ->getMethod('badGetCollectionNotTraversable') 
           ->testGet()
           ;
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     * @expectedExceptionMessage The method 'badGetMethodReturnNotArray' must return a Countable and Traversable object or an array.
     */
    public function testBadGetMethodReturnNotArray()
    {
        $this->collectionTester
           ->getMethod('badGetMethodReturnNotArray') 
           ->testGet()
           ;
    }
}

