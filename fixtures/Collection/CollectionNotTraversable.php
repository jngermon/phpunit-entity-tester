<?php

namespace PhpUnitEntityTester\Fixtures\Collection;

class CollectionNotTraversable implements \Countable
{
    protected $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function count() {}
}

