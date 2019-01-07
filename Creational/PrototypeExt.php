<?php

namespace DesignPatterns\Creational;

use DateTime;

/**
 * Page class has lots of private fields, which will be copied to the cloned object
 */
class Page
{
    private $title;
    private $body;
    private $comments = [];
    private $date;
    /** @var Author */
    private $author;

    public function __construct($title, $body, $author)
    {
        $this->title = $title;
        $this->body = $body;
        $this->author = $author;
        $this->author->addToPage($this);
        $this->date = new DateTime();
    }

    public function addComment($comment)
    {
        $this->comments[] = $comment;
    }

    /**
     * Magic method creates a copy of an object.
     * Here we can control what data should be copied to the cloned object
     */
    public function __clone()
    {
        $this->title = $this->title . '(copy)';
        $this->author->addToPage($this);
        $this->comments = [];
        $this->date = new \DateTime();
    }

    public function render(): string
    {
        return "Title: {$this->title}\n"
            . "Body: {$this->body}\n"
            . "Author: {$this->author->name} Date: {$this->date->format('Y-m-d')}\n"
            . "Comments: " . implode(', ', $this->comments) . "\n";
    }

    public function __toString()
    {
        return $this->title;
    }
}

class Author
{
    public $name;
    /** @var Page[] */
    private $pages = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addToPage(Page $page)
    {
        $this->pages[] = $page;
    }

    public function getPages(): string
    {
        return 'Pages: ' . implode(', ', $this->pages);
    }
}

# Client code example
// Example shows how to clone a complex Page object using the Prototype pattern
$author = new Author('John Doe');
$page = new Page('Article', 'Some text.', $author);
$page->addComment('1st comment');
echo $page->render();
/* Output:
Title: Article
Body: Some text.
Author: John Doe Date: 2018-10-01
Comments: 1st comment */

// Prototype pattern is available in PHP out of the box,
// we can use the `clone` keyword to create an exact copy of an object
$pageCopy = clone $page;
echo $pageCopy->render();
/* Output:
Title: Article(copy)
Body: Some text.
Author: John Doe Date: 2018-10-01
Comments: */

echo $author->getPages();
/* Output: Pages: Article, Article(copy) */