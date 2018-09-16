<?php

namespace DesignPatterns\Structural;

/**
 * Adapter changes the interface of an object to adapt it to another interface.
 * It is often used to make existing classes work with others without modifying their code
 */
interface BookInterface
{
    public function open();
    public function turnPage();
}

class Book implements BookInterface
{
    public function open()
    {
        return "Open the book..\n";
    }

    public function turnPage()
    {
        return "Go to the next page..\n";
    }
}

/**
 * E-book has an other interface
 */
class Kindle
{
    // do the same as open() in real book
    public function turnOn()
    {
        return "Turn on the Kindle..\n";
    }

    // do the same as turnPage() in  real book
    public function pressNextButton()
    {
        return "Press next button on Kindle..\n";
    }
}

class KindleAdapter implements BookInterface
{
    protected $kindle;

    // injecting
    public function __construct(Kindle $kindle)
    {
        $this->kindle = $kindle;
    }

    public function open()
    {
        return $this->kindle->turnOn();
    }

    public function turnPage()
    {
        return $this->kindle->pressNextButton();
    }
}

# Client code example
$book = new Book();
echo $book->open();
echo $book->turnPage();

// transform Kindle e-book to the 'simple book' interface
$book = new KindleAdapter(new Kindle());
echo $book->open();
echo $book->turnPage();
/* Output:
Open the book..
Go to the next page..
Turn on the Kindle..
Press next button on Kindle.. */