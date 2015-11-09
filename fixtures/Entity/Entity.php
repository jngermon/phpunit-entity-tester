<?php

namespace PhpUnitEntityTester\Fixtures\Entity;

class Entity
{
    protected $name;

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setNameNotFluent($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSpecialName()
    {
        return $this->name . 'Special';
    }
}

