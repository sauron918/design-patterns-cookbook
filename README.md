# Design Patterns Cookbook

This is a collection of the most popular Design Patterns with examples in PHP. I tried to bring more understandable examples from real life.

## Creational Patterns

### Singleton

**Singleton** - lets you ensure that a class has **only one instance** and providing a **global access** to this instance. The key idea in this pattern is to make the class itself responsible for controlling its instantiation and prevent re-instantiation.

#### Example
```php
// there is the only one way to get an application instance
$app = Application::getInstance();

$secondApp = Application::getInstance();
if ($secondApp === $app) {
    echo 'It\'s the same instance';
}
/* Output: It's the same instance */

/*  Next calls will produce errors:
    $app2 = new Application();
    $app2 = clone $app;
    $app2 = unserialize(serialize($app)); */
```
[Full example](Creational/Singleton.php)

## Structural Patterns

## Behavioral Patterns