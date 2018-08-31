<?php

namespace DesignPatterns\Creational;

/**
 * Extended example of Builder design pattern with Director
 */
class Director
{
    protected $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Tells the builder what to do (desired sequence)
     */
    public function construct()
    {
        $this->builder->addHeader('header');
        $this->builder->addContent('content');
        $this->builder->addFooter('footer');
    }
}

abstract class Builder
{
    abstract public function addHeader(string $header);

    abstract public function addContent(string $content);

    abstract public function addFooter(string $footer);
}

class HTMLPageBuilder extends Builder
{
    /**
     * @var Page
     */
    private $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    public function addHeader(string $header)
    {
        $this->page->header = '<header>' . $header . '</header>';
    }

    public function addContent(string $content)
    {
        $this->page->content = '<article>' . $content . '</article>';
    }

    public function addFooter(string $footer)
    {
        $this->page->footer = '<footer>' . $footer . '</footer>';
    }
}

class Page
{
    public $title;
    public $header;
    public $content;
    public $footer;

    public function show(): string
    {
        $result = $this->title;
        $result .= $this->header;
        $result .= $this->content;
        $result .= $this->footer;

        return $result;
    }
}

$page = new Page();
$director = new Director(new HTMLPageBuilder($page));
$director->construct();

echo $page->show();
/* Output:
<header>header</header><article>content</article><footer>footer</footer> */