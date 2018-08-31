<?php

namespace DesignPatterns\Behavioral;

/**
 * Editor which have possibility to save and restore the state if necessary.
 */
class Editor
{
    protected $content = '';

    public function type(string $words)
    {
        $this->content = $this->content . ' ' . $words;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Saves the current state inside a memento snapshot
     */
    public function save(): EditorMemento
    {
        return new EditorMemento($this->content);
    }

    /**
     * Restores the editor's state from a memento object
     * @param EditorMemento $memento
     */
    public function restore(EditorMemento $memento)
    {
        $this->content = $memento->getContent();
    }
}

/**
 * The Memento interface provides a way to retrieve the memento's metadata, such as creation date
 */
interface Memento
{
    public function getDate();
}

/**
 * Concrete Memento contains the infrastructure for storing the editor's state.
 * Tip: in real life you probably prefer to make a copy of an object's state easier
 * by simply using a PHP serialization.
 */
class EditorMemento implements Memento
{
    protected $content;
    protected $date;

    public function __construct(string $content)
    {
        $this->content = $content;
        $this->date = date('Y-m-d');
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getDate()
    {
        return $this->date;
    }
}


# Client code example
$editor = new Editor();
$editor->type('This is the first sentence.');
$editor->type('This is second.');
$saved = $editor->save();
$editor->type('And this is third.');
echo $editor->getContent() . PHP_EOL;
/* Output:
This is the first sentence. This is second. And this is third. */

$editor->restore($saved);
echo $editor->getContent() . PHP_EOL;
/* Output:
This is the first sentence. This is second. */