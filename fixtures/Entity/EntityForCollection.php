<?php

namespace PhpUnitEntityTester\Fixtures\Entity;

use PhpUnitEntityTester\Fixtures\Collection\CollectionNotCountable;
use PhpUnitEntityTester\Fixtures\Collection\CollectionNotTraversable;

class EntityForCollection
{
    protected $tests;
    protected $collection;
    protected $collectionNotCountable;
    protected $collectionNotTraversable;

    public function __construct(
        
    ) {
       $this->tests = [];
       $this->collectionNotCountable = new CollectionNotCountable();
       $this->collectionNotTraversable = new CollectionNotTraversable();
    }

    public function addTest($test)
    {
        $this->tests[] = $test;

        return $this;
    }

    public function addTestNotFluent($test)
    {
        $this->tests[] = $test;
    }

    public function addTestUnique($test)
    {
        if (!in_array($test, $this->tests)) {
            $this->tests[] = $test;
        }

        return $this;
    }

    public function badAddTest($test)
    {
        // Forget code to add correctly the test

        return $this;
    }

    public function removeTest($test)
    {
        $this->tests = array_filter($this->tests, function($localtest) use ($test) {
            return $test != $localtest;
        });

        return $this;
    }

    public function removeTestNotFluent($test)
    {
        $this->tests = array_filter($this->tests, function($localtest) use ($test) {
            return $test != $localtest;
        });
    }

    public function badRemoveTest($test)
    {
        //Forget code to remove correctly the test

        return $this;
    }

    public function badRemoveTestReset($test)
    {
        //Remove all elements

        $this->tests = [];

        return $this;
    }

    public function getTests()
    {
        return $this->tests;
    }

    public function badGetTests()
    {
        // Forget code to return tests
    }

    public function badGetCollectionNotCountable()
    {
        return $this->collectionNotCountable;
    }

    public function badGetCollectionNotTraversable()
    {
        return $this->collectionNotTraversable;
    }
    
    public function badGetMethodReturnNotArray()
    {
        return "foo";
    }
}

