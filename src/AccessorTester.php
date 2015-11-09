<?php

namespace PhpUnitEntityTester;

class AccessorTester extends \PhpUnit_Framework_TestCase
{
    protected $entity;
    protected $attribute;
    protected $fluent;
    protected $setterMethod;
    protected $getterMethod;

    public function __construct(
        $entity,
        $attribute
    ) {
        $this->entity = $entity;
        $this->attribute = $attribute;

        $this->fluent = true;

        $this->setterMethod = 'set' . ucfirst($attribute);
        $this->getterMethod = 'get' . ucfirst($attribute);
    }
    
    public function fluent($fluent)
    {
        $this->fluent = $fluent;

        return $this;
    }

    public function setterMethod($setterMethod)
    {
        $this->setterMethod = $setterMethod;

        return $this;
    }

    public function getterMethod($getterMethod)
    {
        $this->getterMethod = $getterMethod;

        return $this;
    }

    public function test($setData, $getData = null)
    {
        $getData = $getData ?: $setData;

        $this->testSetter($setData);
        $this->testGetter($getData);

        return $this;
    }

    private function testSetter($data)
    {
        $setterMethod = $this->setterMethod;

        $this->assertEquals(
            $this->fluent ? $this->entity : null,
            $this->entity->$setterMethod($data)
        );
    }

    private function testGetter($data)
    {
        $getterMethod = $this->getterMethod;

        $this->assertEquals(
            $data,
            $this->entity->$getterMethod()
        );
    }
}

