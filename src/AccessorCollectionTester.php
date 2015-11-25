<?php

namespace PhpUnitEntityTester;

use \PHPUnit_Framework_AssertionFailedError as AssertionFailedError;
use \PHPUnit_Framework_TestCase as TestCase;

class AccessorCollectionTester
{
    public static $MSG_ADD_METHOD_NOT_FLUENT = "The method '%addMethod%' is not fluent.";
    public static $MSG_ADD_METHOD_DOES_NOT_ADD = "The method '%addMethod%' does not add data.";
    public static $MSG_ADD_METHOD_NOT_UNIQUE = "The method '%addMethod%' doesn't respect unicity.";
    public static $MSG_ADD_METHOD_UNIQUE = "The method '%addMethod%' respect unicity for nothing.";
    public static $MSG_REMOVE_METHOD_NOT_FLUENT = "The method '%removeMethod%' is not fluent.";
    public static $MSG_REMOVE_METHOD_DOES_NOT_REMOVE = 
        "The method '%removeMethod%' does not remove data.";
    public static $MSG_REMOVE_METHOD_DOES_NOT_REMOVE_GOOD_NUMBER_OF_ITEMS = 
        "The method '%removeMethod%' does not remove the good number of items.";
    public static $MSG_GET_METHOD_MUST_RETURN_COUNTABLE_OBJECT = 
        "The method '%getMethod%' must return an instance of interface 'Coutable'.";
    public static $MSG_GET_METHOD_MUST_RETURN_TRAVERSABLE_OBJECT = 
        "The method '%getMethod%' must return an instance of interface 'Traversable'.";
    public static $MSG_GET_METHOD_MUST_RETURN_AN_ARRAY = 
        "The method '%getMethod%' must return a Countable and Traversable object or an array.";
    public static $MSG_GET_METHOD_MUST_NOT_RETURN_NULL =
        "The method '%getMethod%' must not return null.";
    
    protected $entity;
    protected $attribute;
    protected $fluent;
    protected $unique;
    protected $addMethod;
    protected $removeMethod;
    protected $getMethod;

    public function __construct(
        $entity,
        $attribute,
        $singular = null
    ) {
        $this->entity = $entity;
        $this->attribute = $attribute;
        
        $this->fluent = true;  
        $this->unique = true;

        $singular = $singular ?: preg_replace('/s$/', '', $attribute);

        $this->addMethod = 'add' . ucfirst($singular);
        $this->removeMethod = 'remove' . ucfirst($singular);
        $this->getMethod = 'get' . ucfirst($attribute);
    }
    
    public function fluent($fluent)
    {
        $this->fluent = $fluent;

        return $this;
    }

    public function unique($unique)
    {
        $this->unique = $unique;

        return $this;
    }

    public function addMethod($addMethod)
    {
        $this->addMethod = $addMethod;
     
        return $this;
    }

    public function removeMethod($removeMethod)
    {
        $this->removeMethod = $removeMethod;

        return $this;
    }

    public function getMethod($getMethod)
    {
        $this->getMethod = $getMethod;

        return $this;
    }

    public function test($firstData, $secondData)
    {
        $count = count($this->entityGet());
        
        // Add first data
        $this->testAdd($firstData);

        // Test the removal of data that is not in the collection
        $this->testRemove($secondData);

        // Add secondary data
        $this->testAdd($secondData);

        // Try to add again the first data (to test unique constraint)
        $this->testAdd($firstData);

        // Test to remove the first data
        $this->testRemove($firstData);

        // Add the first data, the collection contains the first and the second data
        $this->testAdd($firstData);

        return $this;
    }
     
    public function testAdd($data)
    {
        $count = count($this->entityGet());
        $containsBeforeAdd = $this->countDataInCollection($data);

        $returnOfAddMethod = $this->entityAdd($data);

        if ($this->fluent) {
            TestCase::assertEquals(
                $this->entity,
                $returnOfAddMethod,
                $this->msg(self::$MSG_ADD_METHOD_NOT_FLUENT)
            );
        }

        TestCase::assertContains(
            $data, 
            $this->entityGet(),
            $this->msg(self::$MSG_ADD_METHOD_DOES_NOT_ADD)
        );
        
        if ($this->unique && $containsBeforeAdd > 0) {
            TestCase::assertCount(
                $count,
                $this->entityGet(),
                $this->msg(self::$MSG_ADD_METHOD_NOT_UNIQUE)
            );
        } else {
            TestCase::assertCount(
                $count + 1,
                $this->entityGet(),
                $this->msg(self::$MSG_ADD_METHOD_UNIQUE)
            );
        }

        return $this;
    }
    
    public function testRemove($data)
    {
        $count = count($this->entityGet());
        $countBeforeRemove = $this->countDataInCollection($data);

        $returnOfRemoveMethod = $this->entityRemove($data);

        if ($this->fluent) {
            TestCase::assertEquals(
                $this->entity,
                $this->entityRemove($data),
                $this->msg(self::$MSG_REMOVE_METHOD_NOT_FLUENT)
            );
        }

        TestCase::assertNotContains(
            $data, 
            $this->entityGet(),
            $this->msg(self::$MSG_REMOVE_METHOD_DOES_NOT_REMOVE)
        );

        TestCase::assertCount(
            $count - $countBeforeRemove, 
            $this->entityGet(),
            $this->msg(self::$MSG_REMOVE_METHOD_DOES_NOT_REMOVE_GOOD_NUMBER_OF_ITEMS)
        );

        return $this;
    }

    public function testGet()
    {
        $get = $this->entityGet();

        if (is_object($get)) {
            TestCase::assertInstanceOf(
                "Countable", 
                $get,
                $this->msg(self::$MSG_GET_METHOD_MUST_RETURN_COUNTABLE_OBJECT)
            );
            TestCase::assertInstanceOf(
                "Traversable",
                $get,
                $this->msg(self::$MSG_GET_METHOD_MUST_RETURN_TRAVERSABLE_OBJECT)
            );
        } elseif (!is_null($get)) {
            TestCase::assertInternalType(
                "array", 
                $get,
                $this->msg(self::$MSG_GET_METHOD_MUST_RETURN_AN_ARRAY)
            );
        } else {
            throw new AssertionFailedError(
                $this->msg(self::$MSG_GET_METHOD_MUST_NOT_RETURN_NULL)
             );
        }
    }

    private function entityGet()
    {
        $getMethod = $this->getMethod;

        return $this->entity->$getMethod();
    }

    private function entityAdd($data)
    {
        $addMethod = $this->addMethod;

        return $this->entity->$addMethod($data);
    }

    private function entityRemove($data)
    {
        $removeMethod = $this->removeMethod;

        return $this->entity->$removeMethod($data);
    }

    private function countDataInCollection($data)
    {
        $count = 0;

        foreach ($this->entityGet() as $item) {
            if ($item == $data) {
                $count++;
            }
        }

        return $count;
    }

    private function msg($msg)
    {
        $replaces = [
            '%addMethod%' => $this->addMethod,
            '%removeMethod%' => $this->removeMethod,
            '%getMethod%' => $this->getMethod
        ];

        foreach ($replaces as $key => $value) {
            $msg = str_replace($key, $value, $msg);
        }

        return $msg;
    }
}

