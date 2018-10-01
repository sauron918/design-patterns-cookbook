<?php

namespace DesignPatterns\Creational;

/**
 * Abstract Factory defines an interface for creating all distinct products,
 * but leaves the actual product creation to concrete factory classes
 */
interface TemplateFactory
{
    public function createHeader(): Header;

    public function createBody(): Body;
}

/**
 * Each factory type corresponds to a certain product variety
 */
class SmartyTemplateFactory implements TemplateFactory
{
    public function createHeader(): Header
    {
        return new SmartyHeader();
    }

    public function createBody(): Body
    {
        return new SmartyBody();
    }
}

class BladeTemplateFactory implements TemplateFactory
{
    public function createHeader(): Header
    {
        return new BladeHeader();
    }

    public function createBody(): Body
    {
        return new BladeBody();
    }
}

/**
 * The base interface for header (products) family
 */
interface Header
{
    public function render(): string;
}

class SmartyHeader implements Header
{
    public function render(): string
    {
        return '<h1>{$title}</h1>';
    }
}

class BladeHeader implements Header
{
    public function render(): string
    {
        return '<h1>{{ $title }}</h1>';
    }
}

/**
 * Another products family
 */
interface Body
{
    public function render(): string;
}

class SmartyBody implements Body
{
    public function render(): string
    {
        return '<main>{$content}</main>';
    }
}

class BladeBody implements Body
{
    public function render(): string
    {
        return '<main>{{ $content }}</main>';
    }
}

# Client code example
// the factory is selected based on the environment or configuration parameters
$templateEngine = 'blade';
switch ($templateEngine) {
    case 'smarty':
        $templateFactory = new SmartyTemplateFactory();
        break;
    case 'blade':
        $templateFactory = new BladeTemplateFactory();
        break;
}

// we will have header and body as either Smarty or Blade template, but never mixed
echo ($templateFactory->createHeader())->render();
echo ($templateFactory->createBody())->render();
/* Output: <h1>{{ $title }}</h1><main>{{ $content }}</main> */