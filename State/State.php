<?php

namespace DesignPatterns\Behavioral;

/**
 * Editor contains a reference to an instance of a State interface,
 * which represents the current editor state (e.g. upper case, lower case or default)
 */
class TextEditor
{
    protected $state;

    public function __construct(WritingState $state)
    {
        $this->state = $state;
    }

    /**
     * Allows changing the editor state at runtime
     * @param WritingState $state
     */
    public function setState(WritingState $state)
    {
        $this->state = $state;
    }

    /**
     * Just printing words based on current state
     * @param string $words
     */
    public function type(string $words)
    {
        $this->state->write($words);
    }
}

/**
 * State interface declares methods that all Concrete State should implement
 */
interface WritingState
{
    public function write(string $words);
}


class DefaultText implements WritingState
{
    public function write(string $words)
    {
        echo $words . PHP_EOL;
    }
}

class UpperCase implements WritingState
{
    public function write(string $words)
    {
        echo strtoupper($words) . PHP_EOL;
    }
}

class LowerCase implements WritingState
{
    public function write(string $words)
    {
        echo strtolower($words) . PHP_EOL;
    }
}

# Client code example
$editor = new TextEditor(new DefaultText());
$editor->type('First line');

$editor->setState(new UpperCase());
$editor->type('Second line');

$editor->setState(new LowerCase());
$editor->type('Third line');

/* Output:
First line
SECOND LINE
third line */


