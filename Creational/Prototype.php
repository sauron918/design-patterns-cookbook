<?php

namespace DesignPatterns\Creational;

class Page
{
    protected $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * Cloning method, each object should implement how it will be cloned himself
     */
    public function getClone(): self
    {
        return new static($this->title);
    }

    public function getName()
    {
        return $this->title;
    }

    public function __toString()
    {
        return $this->title;
    }
}

# Client code example
$page = new Page('Page Title');

echo $pageClone = $page->getClone();
/* Output: Page Title */