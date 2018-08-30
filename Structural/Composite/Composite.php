<?php

namespace DesignPatterns\Structural;

abstract class CartItem
{
    protected $parent;

    public function setParent(CartItem $parent)
    {
        $this->parent = $parent;
    }

    public function getParent(): CartItem
    {
        return $this->parent;
    }

    public function add(CartItem $cartItem)
    {
    }

    public function remove(CartItem $cartItem)
    {
    }

    public function isComposite(): bool
    {
        return false;
    }

    abstract public function getPrice(): float;
}

class Product extends CartItem
{
    protected $name;
    protected $price;

    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}

class CompositeProduct extends CartItem
{
    protected $name;

    /** @var CartItem[] */
    protected $children = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function add(CartItem $cartItem)
    {
        if (in_array($cartItem, $this->children, true)) {
            return;
        }
        $this->children[] = $cartItem;
        $cartItem->setParent($this);
    }

    public function remove(CartItem $cartItem)
    {
        $this->children = array_filter($this->children, function ($child) use ($cartItem) {
            return $child == $cartItem;
        });
        $cartItem->setParent(null);
    }

    public function getPrice(): float
    {
        $totalPrice = 0;
        foreach ($this->children as $child) {
            $totalPrice += $child->getPrice();
        }

        return $totalPrice;
    }

    public function isComposite(): bool
    {
        return true;
    }
}

# Client code example
$shoppingCart[] = new Product('Bike', 200);

$motorcycle = new CompositeProduct('Motorcycle');
$motorcycle->add(new Product('Motor', 700));
$motorcycle->add(new Product('Wheels', 300));

$frame = new CompositeProduct('Frame');
$frame->add(new Product('Steering', 200.00));
$frame->add(new Product('Seat', 100));
$motorcycle->add($frame);
$shoppingCart[] = $motorcycle;


// calculate a total price of shopping cart
$totalPrice = 0;
foreach ($shoppingCart as $cartItem) {
    /** @var CartItem $cartItem */
    $totalPrice += $cartItem->getPrice();
}
echo $totalPrice;   // Output: 1500