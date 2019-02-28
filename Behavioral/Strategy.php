<?php

namespace DesignPatterns\Behavioral;

/**
 * Strategy interface declares operations common to all supported versions of some algorithm
 */
interface SortStrategy
{
    public function sort(array $data): array;
}

/**
 * Concrete strategy, implements bubble sorting algorithm
 */
class BubbleSortStrategy implements SortStrategy
{
    public function sort(array $data): array
    {
        // stub
        echo 'Sorting using bubble sort..';

        return $data;
    }
}

/**
 * Concrete strategy, implements quick sorting algorithm
 */
class QuickSortStrategy implements SortStrategy
{
    public function sort(array $data): array
    {
        // stub
        echo 'Sorting using quick sort..';

        return $data;
    }
}

/**
 * Client maintains a reference to one of the Strategy objects.
 * It should work with Strategy interface only.
 */
class Sorter
{
    protected $sortStrategy;

    /**
     * Context accepts a strategy through the constructor
     * @param SortStrategy $sortStrategy
     */
    public function __construct(SortStrategy $sortStrategy)
    {
        $this->sortStrategy = $sortStrategy;
    }

    /**
     * But also provides a setter to change strategy at runtime
     * @param SortStrategy $sortStrategy
     */
    public function setStrategy(SortStrategy $sortStrategy)
    {
        $this->sortStrategy = $sortStrategy;
    }

    /**
     * Client delegates some work to the Strategy object instead of
     * implementing multiple versions of the algorithm on its own
     * @param array $data
     * @return array
     */
    public function sortArray(array $data): array
    {
        return $this->sortStrategy->sort($data);
    }
}

# Client code example
$data = [4, 2, 1, 5, 9];

// for small amount of data the "Bubble Sort" algorithm will be used
// and for large amounts - the "Quick Sort" algorithm
if (count($data) < 10) {
    $sorter = new Sorter(new BubbleSortStrategy());
    $sorter->sortArray($data);
} else {
    $sorter = new Sorter(new QuickSortStrategy());
    $sorter->sortArray($data);
}

/* Output: Sorting using bubble sort.. */


