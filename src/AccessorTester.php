<?php

namespace PhpUnitEntityTester;

use \PhpUnit_Framework_TestCase as Testcase;

class AccessorTester
{
    const USE_SET_DATA = 'USE_SET_DATA';

    public static $MSG_SETTER_METHOD_NOT_FLUENT = "The method '%setterMethod%' is not fluent.";
    public static $MSG_GETTER_METHOD_BAD_RETURN = "The method '%getterMethod%' does not return the good value.";

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

    public function test($setData, $getData = self::USE_SET_DATA)
    {
        $getData = $getData == self::USE_SET_DATA ? $setData : $getData;

        $this->testSetter($setData);
        $this->testGetter($getData);

        return $this;
    }

    private function testSetter($data)
    {
        $setterMethod = $this->setterMethod;

        $returnOfSetter = $this->entity->$setterMethod($data);

        if ($this->fluent) {
            TestCase::assertEquals(
                $this->entity,
                $returnOfSetter,
                $this->msg(self::$MSG_SETTER_METHOD_NOT_FLUENT)
            );
        }
    }

    private function testGetter($data)
    {
        $getterMethod = $this->getterMethod;

        TestCase::assertEquals(
            $data,
            $this->entity->$getterMethod(),
            $this->msg(self::$MSG_GETTER_METHOD_BAD_RETURN)
        );
    }

    private function msg($msg)
    {
        $replaces = [
            '%setterMethod%' => $this->setterMethod,
            '%getterMethod%' => $this->getterMethod
        ];

        foreach ($replaces as $key => $value) {
            $msg = str_replace($key, $value, $msg);
        }

        return $msg;
    }
}

