<?php

namespace DesignPatterns\Structural;

/**
 * Decorator pattern lets you dynamically change the behavior of an object at run time
 * by wrapping them in an object of a decorator class
 */
interface Coffee
{
    public function getCost();
    public function getDescription();
}

class SimpleCoffee implements Coffee
{
    public function getCost()
    {
        return 10;
    }

    public function getDescription()
    {
        return 'Coffee';
    }
}

class MilkCoffee implements Coffee
{
    protected $coffee;

    // injection
    public function __construct(Coffee $coffee)
    {
        $this->coffee = $coffee;
    }

    // change, "decorate" the default behavior
    public function getCost()
    {
        return $this->coffee->getCost() + 2;
    }

    public function getDescription()
    {
        return $this->coffee->getDescription() . ', with milk';
    }
}

class VanillaCoffee implements Coffee
{
    protected $coffee;

    public function __construct(Coffee $coffee)
    {
        $this->coffee = $coffee;
    }

    public function getCost()
    {
        return $this->coffee->getCost() + 3;
    }

    public function getDescription()
    {
        return $this->coffee->getDescription() . ', with vanilla';
    }
}

# Client code example
$coffee = new SimpleCoffee();
echo $coffee->getDescription() . ' - ' . $coffee->getCost() . PHP_EOL;

// apply the Decorator for the $coffee object
$milkCoffee = new MilkCoffee($coffee);
echo $milkCoffee->getDescription() . ' - ' . $milkCoffee->getCost() . PHP_EOL;

// we also can use chain calls of Decorators
$vanillaMilkCoffee = new VanillaCoffee(new MilkCoffee(new SimpleCoffee()));
echo $vanillaMilkCoffee->getDescription() . ' - ' . $vanillaMilkCoffee->getCost() . PHP_EOL;

/** Output:
Coffee - 10
Coffee, with milk - 12
Coffee, with milk, with vanilla - 15 */