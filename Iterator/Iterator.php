<?php

namespace DesignPatterns\Behavioral;

/**
 * Concrete Iterator implements traversal algorithm
 * \Iterator is built-in PHP interface
 */
class ReverseIterator implements \Iterator
{
    /**
     * Collection instance
     * @var SimpleCollection
     */
    private $collection;

    /**
     * @var int Stores the current traversal position
     */
    private $position = 0;

    public function __construct(SimpleCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind()
    {
        $this->position = count($this->collection->getItems()) - 1;
    }

    /**
     * Return the current element
     */
    public function current()
    {
        return $this->collection->getItems()[$this->position];
    }

    /**
     * Return the key of the current element
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Move forward to next element
     */
    public function next()
    {
        $this->position = $this->position - 1;
    }

    /**
     * Checks if current position is valid
     */
    public function valid()
    {
        return isset($this->collection->getItems()[$this->position]);
    }
}

/**
 * Concrete Collection provides one or several methods for working with collection
 * and also implements a built-in \IteratorAggregate interface which guarantee what we can fetch 'right' iterator
 */
class SimpleCollection implements \IteratorAggregate
{
    private $items = [];

    public function addItem($item)
    {
        $this->items[] = $item;
        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getIterator(): \Iterator
    {
        return new ReverseIterator($this);
    }
}


# Client code example
$collection = (new SimpleCollection())->addItem('1st item')
    ->addItem('2nd item')
    ->addItem('3rd item');

// Go through collection in reverse order
foreach ($collection->getIterator() as $item) {
    echo $item . PHP_EOL;
}

/* Output:
3rd item
2nd item
1st item */