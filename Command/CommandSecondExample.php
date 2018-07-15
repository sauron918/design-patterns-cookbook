<?php

namespace DesignPatterns\Behavioral;

/**
 * Abstract command
 */
abstract class ExtendedCommand
{
    protected $receiver;
    protected $params;

    /**
     * Constructor
     * @param Receiver $receiver Receiver of command
     * @param mixed ...$params Parameters that may be needed to Receiver
     */
    public function __construct(Receiver $receiver, ...$params)
    {
        $this->receiver = $receiver;
        $this->params = $params;
    }

    /**
     * We should have possibility to execute command and rollback if necessary
     */
    public abstract function execute();
    public abstract function rollback();
}

/**
 * Concrete command, doesn't do all the work by self and only passes the call to the receiver
 */
class TurnOnCommand extends ExtendedCommand
{
    public function execute()
    {
        $this->receiver->turnOn($this->params);
    }

    public function rollback()
    {
        $this->receiver->turnoff($this->params);
    }
}

/*
 * Invoker of commands
 */
class Invoker
{
    /**
     * @var []Command Queue of commands
     */
    protected $commands = [];

    public function pushCommand(ExtendedCommand $command)
    {
        return array_push($this->commands, $command);
    }

    /**
     * Executes last command
     */
    public function executeCommand()
    {
        /** @var ExtendedCommand $lastCommand */
        if ($lastCommand = array_pop($this->commands)) {
            return $lastCommand->execute();
        }

        return false;
    }

    /**
     * Rollbacks last command
     */
    public function rollbackCommand()
    {
        /** @var ExtendedCommand $lastCommand */
        if ($lastCommand = array_pop($this->commands)) {
            return $lastCommand->rollback();
        }

        return false;
    }
}

/**
 * Receiver of commands, contains some business logic
 */
class Receiver
{
    public function turnOn($params)
    {
        echo "Receiver: Turning on something with params: ". implode(', ', $params) . PHP_EOL;
    }

    public function turnOff($params)
    {
        echo "Receiver: Turning off something with params: ". implode(', ', $params) . PHP_EOL;
    }
}

# Client code example
$invoker = new Invoker();
$receiver = new Receiver();

$invoker->pushCommand(new TurnOnCommand($receiver, 'some_param'));
$invoker->executeCommand();
$invoker->pushCommand(new TurnOnCommand($receiver, 'kill', -9));
$invoker->rollbackCommand();

/* Output:
Receiver: Turning on something with params: some_param
Receiver: Turning off something with params: kill, -9 */