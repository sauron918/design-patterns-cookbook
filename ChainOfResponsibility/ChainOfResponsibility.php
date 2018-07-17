<?php

namespace DesignPatterns\Behavioral;

/**
 * Declares a basic method for building the chain of handlers and method for executing a request
 */
interface Handler
{
    public function setNext(Handler $handler);

    public function handle($message);
}

/**
 * An optional class that eliminates the duplication of the same code in all specific handlers
 */
abstract class AbstractLogger implements Handler
{
    /**
     * @var Handler
     */
    private $nextHandler;

    /**
     * Sets next handler and returns its instance
     * @param Handler $handler
     * @return Handler
     */
    public function setNext(Handler $handler)
    {
        $this->nextHandler = $handler;

        // it will let us link handlers in a convenient way like: $handler->setNext()->setNext()
        return $handler;
    }

    /**
     * Calls next handler if present
     * @param $message
     * @return bool
     */
    public function handle($message)
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($message);
        }

        return false;
    }
}

/**
 * Logs message to database if possible and execute next handler
 */
class DBLogger extends AbstractLogger
{
    /**
     * Stub method
     * @return bool
     */
    public function canSave()
    {
        // you can play with true/false value
        return false;
    }

    public function handle($message)
    {
        if ($this->canSave()) {
            echo "Save message to database..\n";
        }

        return parent::handle($message);
    }
}

/**
 * Sends message by mail if possible and executes next handler
 */
class MailLogger extends AbstractLogger
{
    /**
     * Stub method
     * @return bool
     */
    public function canMail()
    {
        return true;
    }

    public function handle($message)
    {
        if ($this->canMail()) {
            echo 'Send message by email..' . PHP_EOL;
        }

        return parent::handle($message);
    }
}

/**
 * Logs message to log file if possible and executes next handler if present
 */
class FileLogger extends AbstractLogger
{
    /**
     * Stub method
     * @return bool
     */
    public function canWrite()
    {
        return true;
    }

    public function handle($message)
    {
        if ($this->canWrite()) {
            echo "Save message to log file..\n";
        }

        return parent::handle($message);
    }
}

# Client code example

/**
 * Simple logger
 */
class Logger
{
    public static function log($message)
    {
        // build the chain
        $logger = new DBLogger();
        $logger->setNext(new MailLogger())
            ->setNext(new FileLogger());

        // call the first handler
        $logger->handle($message);
    }
}

Logger::log('Message text');
/* Output:
Send message by email..
Save message to log file.. */