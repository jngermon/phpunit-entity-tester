<?php

namespace PhpUnitEntityTester\Fixtures\Collection;

class CollectionNotCountable implements \IteratorAggregate
{
    protected $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function getIterator() {}
}

