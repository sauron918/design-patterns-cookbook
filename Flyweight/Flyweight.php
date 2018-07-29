<?php

namespace DesignPatterns\Structural;

/**
 * Simple shopping cart.
 *
 * Pay your attention that some product data (brand & brandLogo)
 * may be duplicated for multiple products. It can leads to inefficient memory usage,
 * so we transfer duplicate product data into one single object and refer to it
 * from a individual product.
 */
class ShoppingCart
{
    /** @var Product[] */
    protected $products = [];

    public function addProduct($title, $price, $brand, $brandLogo)
    {
        $productBrand = ProductFactory::getBrand($brand, $brandLogo);
        $this->products[] = ProductFactory::getProduct($title, $price, $productBrand);
    }

    public function getProducts()
    {
        return $this->products;
    }
}

/**
 * Contains main part of product state,
 * brandName and brandLogo will be shared between many products
 */
class Product
{
    protected $title;
    protected $price;
    /** @var ProductBrand */
    protected $brand;

    public function __construct($title, $price, ProductBrand $brand)
    {
        $this->title = $title;
        $this->price = $price;
        $this->brand = $brand;
    }
}

/**
 * This Flyweight class contains a portion of a state of a product.
 */
class ProductBrand
{
    private $brandName;
    private $brandLogo;

    public function __construct($brandName, $brandLogo)
    {
        $this->brandName = $brandName;
        $this->brandLogo = $brandLogo;
    }
}

/**
 * Factory decides when to create a new object,
 * and when it is possible to do with the existing one.
 * It saves memory (especially when we need to store
 * a large list of products in memory)
 */
class ProductFactory
{
    public static $brandTypes = [];

    public static function getBrand($brandName, $brandLogo)
    {
        if (isset(static::$brandTypes[$brandName])) {
            return static::$brandTypes[$brandName];
        }

        return static::$brandTypes[$brandName] = new ProductBrand($brandName, $brandLogo);
    }

    public static function getProduct($title, $price, $productBrand)
    {
        return new Product($title, $price, $productBrand);
    }
}

# Client code example
$shoppingCart = new ShoppingCart();
$shoppingCart->addProduct('Sports shoes', 120, 'Nike', 'Nike.png');
$shoppingCart->addProduct('Kids shoes', 100, 'Nike', 'Nike.png');
$shoppingCart->addProduct('Women shoes', 110, 'Nike', 'Nike.png');
$shoppingCart->addProduct('Running shoes', 140, 'Asics', 'Asics.jpg');
$shoppingCart->addProduct('Everyday shoes', 90, 'Adidas', 'Adidas.svg');

echo count($shoppingCart->getProducts());   // 5 products in basket
echo count(ProductFactory::$brandTypes);    // and only 3 unique brands instances in memory