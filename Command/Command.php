<?php

namespace DesignPatterns\Behavioral;

/**
 * Command Design Pattern
 *
 * Turns operations into objects and such objects can be queued, canceled or logged.
 * The key idea behind this pattern is to provide the means to decouple client from receiver.
 */

/**
 * Abstract command
 */
abstract class Command
{
    protected $receiver;

    public function __construct(Receiver $receiver)
    {
        $this->receiver = $receiver;
    }

    public abstract function execute();
}

/**
 * Concrete command, doesn't do all the work by self and only passes the call to the receiver
 */
class TurnOnCommand extends Command
{
    public function execute()
    {
        $this->receiver->turnOn();
    }
}

class TurnOffCommand extends Command
{
    public function execute()
    {
        $this->receiver->turnOff();
    }
}

/*
 * An invoker of commands
 */
class Invoker
{
    /**
     * @var []Command Queue of commands
     */
    protected $commands = [];

    public function pushCommand(Command $command)
    {
        $this->commands[] = $command;
    }

    /**
     * Executes all commands from queue
     */
    public function execute()
    {
        foreach ($this->commands as $key => $command) {
            $command->execute();
            unset($this->commands[$key]);
        }
    }
}

/**
 * Receiver of commands, contains some business logic
 */
class Receiver
{
    public function turnOn()
    {
        echo "Receiver: Turning on something..\n";
    }

    public function turnOff()
    {
        echo "Receiver: Turning off something..\n";
    }
}

# Client code example
$invoker = new Invoker();
$receiver = new Receiver();

$invoker->pushCommand(new TurnOnCommand($receiver));
$invoker->pushCommand(new TurnOffCommand($receiver));
$invoker->execute();

/* Output:
Receiver: Turning on something..
Receiver: Turning off something.. */