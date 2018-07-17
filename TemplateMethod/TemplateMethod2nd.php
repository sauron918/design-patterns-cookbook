<?php

/**
 * Builder class demonstrates using of Template Method patter
 * Method build() defines all steps of an algorithm
 */
abstract class Builder
{
    /**
     * Template method
     */
    final public function build()
    {
        $this->create();
        $this->init();
        $this->test();
        $this->deploy();
    }

    protected function create()
    {
        echo 'Creating an application..' . PHP_EOL;
    }

    protected function init()
    {
        echo 'Initialization..' . PHP_EOL;
    }

    protected function test()
    {
        echo 'Running tests..' . PHP_EOL;
    }

    abstract protected function deploy();
}

/**
 * Concrete classes override some operations from the template method algorithm
 */
class AndroidBuilder extends Builder
{
    protected function deploy()
    {
        echo 'Deploying Android application!' . PHP_EOL;
    }
}

class iOSBuilder extends Builder
{
    protected function deploy()
    {
        echo 'Deploying iOS application!' . PHP_EOL;
    }
}


# Client code example
(new AndroidBuilder)->build();
/* Output:
Creating an application..
Initialization..
Running tests..
Deploying Android application! */

(new iOSBuilder())->build();
/* Output:
Creating an application..
Initialization..
Running tests..
Deploying iOS application! */