<?php

namespace DesignPatterns\Creational;

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
    final private function __construct()
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
        $this->foo = 1;
    }
}

$app = Application::getInstance();
if ($app2 = Application::getInstance() === $app) {
    echo '$app2 is the same application instance';
}
/* Output: $app2 is the same application instance */

// Next calls will produce errors:
// $app2 = new Application();
// $app2 = clone $app;
// $app2 = unserialize(serialize($app));