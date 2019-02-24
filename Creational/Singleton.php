<?php

namespace DesignPatterns\Creational;

/**
 * Lets you ensure that a class has only one instance and providing a global access to this instance
 */
trait Singleton
{
    protected static $instance;

    final public static function getInstance()
    {
        return isset(static::$instance)
            ? static::$instance
            : static::$instance = new static;
    }

    /**
     * Singleton's constructor should not be public. However, it can't be
     * private either if we want to allow subclassing.
     */
    protected function __construct()
    {
        $this->init();
    }

    /**
     * Some initialization can be here
     */
    protected function init()
    {
    }

    // Cloning and unserialization are not permitted for singletons
    final private function __clone()
    {
    }

    final private function __wakeup()
    {
    }
}

class Application
{
    use Singleton;

    protected function init()
    {
        $this->isRunning = true;
    }
}

// there is the only one way to get an application instance
$app = Application::getInstance();

$secondApp = Application::getInstance();
if ($secondApp === $app) {
    echo 'It\'s the same instance';
}
/* Output: It's the same instance */

/*  Next calls will produce errors:
    $app = new Application();
    $app = clone $app;
    $app = unserialize(serialize($app)); */