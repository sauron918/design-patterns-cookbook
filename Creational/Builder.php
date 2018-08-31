<?php

namespace DesignPatterns\Creational;

/**
 * The main idea behind Builder pattern is prevent "telescoping constructor"
 * public function __construct($name, $value, $param1 = true, $param2 = true, $param3 = false, ..) {}
 */
class Page
{
    public $title;
    public $header;
    public $content;
    public $footer;

    public function __construct(PageBuilder $builder)
    {
        $this->title = $builder->title;
        $this->header = $builder->header;
        $this->content = $builder->content;
        $this->footer = $builder->footer;
    }

    public function show(): string
    {
        $result = $this->title;
        $result .= $this->header;
        $result .= $this->content;
        $result .= $this->footer;

        return $result;
    }
}

class PageBuilder
{
    public $title;

    public $header = '';
    public $content = '';
    public $footer = '';

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function addHeader(string $header)
    {
        $this->header = $header;
        return $this;
    }

    public function addContent(string $content)
    {
        $this->content = $content;
        return $this;
    }

    public function addFooter(string $footer)
    {
        $this->footer = $footer;
        return $this;
    }

    public function build(): Page
    {
        return new Page($this);
    }
}

# Client code example
$page = (new PageBuilder('<h1>Home page</h1>'))
    ->addHeader('<header></header>')
    ->addContent('<article>content</article>');

// some time letter ..
$page->addFooter('<footer></footer>');

echo $page->build()->show();
/* Output:
  <h1>Home page</h1><header></header><article>content</article><footer></footer> */