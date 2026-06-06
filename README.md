# Design Patterns Cookbook
A collection of the most popular Design Patterns with descriptions and examples in PHP.


## 1. Creational Patterns

## Singleton
#### The essence of the pattern
Ensures that only one object of a given type exists in the application, and provides a global point of access to it.

#### What problems it solves
* Guarantees that a class has a single instance
* Provides a global point of access

#### Example
```php
// there is the only one way to get an application instance
$app = Application::getInstance();
// every call will give us the same instance
assert(Application::getInstance() === $app);

/* Next calls will produce errors:
$app = new Application();
$app = clone $app;
$app = unserialize(serialize($app)); */
```
[Full example](Creational/Singleton.php)


## Prototype
#### The essence of the pattern
The pattern describes the process of creating clone objects based on an existing prototype object. In other words, the pattern describes how to organize the cloning process. Usually the cloning operation happens through a `getClone()` method, which is declared in the base class.

In PHP the ability to clone objects is [built in](http://php.net/manual/en/language.oop5.cloning.php): using the `clone` keyword you can make an exact copy of an object. To add cloning support to a class, you need to implement the `__clone()` method.

#### What problems it solves
* Lets you copy objects without going into the details of their implementation

#### Example
```php
class Page
{
    public function __construct(string $title)
    {
        $this->title = $title;
    }
    
    // cloning method, each object should implement how he will be cloned himself
    public function getClone()
    {
        return new static($this->title);
    }
}

$page = new Page('Page Title');
echo $pageClone = $page->getClone(); // Page Title
```
[Full example](Creational/Prototype.php) | [Additional example](Creational/PrototypeExt.php)


## Builder
#### The essence of the pattern
Lets you construct complex objects step by step, and also makes it possible to use the same construction code to produce different representations of objects.

#### What problems it solves
* Defines the process of building a complex product step by step
* Lets you get rid of "telescoping" constructors `__construct($param1, $param2, ..., $paramN)`

#### Example
```php
$page = (new PageBuilder('<h1>Home page</h1>'))
    ->addHeader('<header></header>')
    ->addContent('<article>content</article>');

// some time letter ...
$page->addFooter('<footer></footer>');

echo $page->build()->show();
/* Output:
  <h1>Home page</h1><header></header><article>content</article><footer></footer> */
```
[Full example](Creational/Builder.php) | [Additional example](Creational/BuilderExt.php)


## Factory Method
#### The essence of the pattern
Defines a common interface for creating objects in a superclass, allowing subclasses to change the type of objects being created.
It proposes creating objects not directly, using the `new` operator, but through a call to a special factory method. The objects will still be created using `new`, but the factory method will do it. For this system to work, all returned objects must share a common interface.

#### What problems it solves
* Lets you use inheritance and polymorphism to encapsulate the creation of concrete instances
* Solves the problem of creating objects without specifying their concrete classes
* Used when the system must remain easily extensible by adding objects of new types

#### Example
```php
abstract class Response {}
class JSONResponse extends Response {}
class HTMLResponse extends Response {}

interface Formatter {}
class HTMLFormatter implements Formatter {}
class JSONFormatter implements Formatter {}

// type can be taken from configuration for instance,
// JsonResponse will use JSONFormatter (see full example)
echo $response = new JsonResponse('some input data');

/* Output: {"code": 200, "response": "some input data"} */
```
[Full example](Creational/FactoryMethod.php)


## Abstract Factory
#### The essence of the pattern
Lets you create families of related objects without being tied to the concrete classes of the objects being created. In other words, it provides an interface for creating families of related or dependent objects without specifying their concrete classes.
Usually the program creates a concrete factory object at startup, and the type of factory is chosen based on environment or configuration parameters.

#### What problems it solves
* Hides from the client code the details of how and which concrete objects will be created
* Solves the problem of incompatibility between a set of related objects when they are created

#### Example
```php
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
echo $templateFactory->createHeader()->render();
echo $templateFactory->createBody()->render();
/* Output: <h1>{{ $title }}</h1><main>{{ $content }}</main> */
```
[Full example](Creational/AbstractFactory.php) 


## 2. Structural Patterns

## Adapter
#### The essence of the pattern
Adapts existing code to a required interface (acts as an adapter/converter). For example, you have a class whose interface is not compatible with the code of your system; in this case we don't change the code of that class, but write an adapter for it — we "wrap" the object so as to hold a reference to it, and directly convert the object's interface to the required one.
We can create an adapter in either direction: either for some old system, to use its functionality with a new interface, or any new interface to match what an already existing object expects.

#### What problems it solves
* Lets you use a third-party class if its interface is not compatible with existing code
* When you need to use several existing subclasses, but they lack some common functionality (and we can't extend the superclass)

#### Example
```php
$book = new Book();
$book->open();
$book->turnPage();

// transform Kindle e-book to the 'simple book' interface
$book = new KindleAdapter(new Kindle());
echo $book->open();
echo $book->turnPage();

/* Output:
Open the book..
Go to the next page..

Turn on the Kindle..
Press next button on Kindle.. */
```
[Full example](Structural/Adapter.php)


## Decorator
#### The essence of the pattern
Lets you dynamically add new functionality to objects without changing their interface.

#### What problems it solves
* Helps extend a class with some specific behavior without changing its interface
* Lets you add other decorators "in a chain"
* Used when you can't extend an object's responsibilities through inheritance

#### Example
```php
$coffee = new SimpleCoffee();
// apply the Decorator for the $coffee object
$milkCoffee = new MilkCoffee($coffee);
// we also can use chain calls of Decorators
$vanillaMilkCoffee = new VanillaCoffee(new MilkCoffee(new SimpleCoffee()));

/** Output:
Coffee
Coffee, with milk
Coffee, with milk, with vanilla */
```
[Full example](Structural/Decorator.php)


## Facade
#### The essence of the pattern
Provides a simplified interface to a complex system of calls or a complex system of classes.

#### What problems it solves
* Provides a simple or trimmed-down interface to a complex subsystem
* Helps decompose a complex subsystem into "subsystems"

#### Example
```php
class SignUpFacade
{
    public function signUpUser($userName, $userPass, $userMail)
    {
        $this->validator->isValidMail($userMail);
        $this->userService->create($userName, $userPass, $userMail);
        $this->mailService->to($userMail)->subject('Welcome')->send();
    }
}

// we make an abstraction above user registration process
$facade = new SignUpFacade();
$facade->signUpUser('Sergey', '123456', 'test@mail.com');
```
[Full example](Structural/Facade.php)


## Composite
#### The essence of the pattern
Lets you group many objects into a tree structure and then work with it as if it were a single object. The pattern proposes storing references to other simple or composite objects inside composite objects. Those, in turn, can also store their own nested objects, and so on.

The client code works with all objects through a common interface and doesn't know whether it's dealing with a simple or a composite object. This allows the client code to work with object trees of any complexity without being tied to the concrete classes of the objects that form the tree.

#### What problems it solves
* Simplifies working with any tree-like recursive structures
* Lets you treat simple and composite objects uniformly

#### Example
```php
$shoppingCart[] = new Product('Bike', 200);

$motorcycle = new CompositeProduct('Motorcycle');
$motorcycle->add(new Product('Motor', 700));
$motorcycle->add(new Product('Wheels', 300));

$frame = new CompositeProduct('Frame');
$frame->add(new Product('Steering', 200.00));
$frame->add(new Product('Seat', 100));
$motorcycle->add($frame);

$shoppingCart[] = $motorcycle;

// calculate a total price of shopping cart
$totalPrice = 0;
foreach ($shoppingCart as $cartItem) {
    $totalPrice += $cartItem->getPrice();
}
echo $totalPrice;   // Output: 1500
```
[Full example](Structural/Composite.php)


## Bridge
#### The essence of the pattern
Splits one or more classes into two separate hierarchies — abstraction and implementation — allowing them to be changed independently of each other.

The pattern proposes replacing inheritance with delegation. When a class needs to be extended in two independent dimensions, the pattern proposes extracting one of those dimensions into a separate class hierarchy, keeping a reference to one of its objects in the original class.

#### What problems it solves
* Useful in a situation where a class needs to be extended in two independent dimensions
* Lets you split a monolithic class that contains several different implementations into more specialized implementations
* Preserves the ability to swap the implementation at runtime

#### Example
```php
// some 'abstraction' hierarchy
abstract class WebPage 
{
    // ...
    public function __construct(Theme $theme)
    {
        $this->theme = $theme;
    }
}
class HomePage extends WebPage {}
class AboutPage extends WebPage {}

// separate 'implementation' hierarchy
interface Theme {}
class DarkTheme implements Theme {}
class LightTheme implements Theme {}

$homePage = new HomePage(new DarkTheme());
echo $homePage->getContent();       // Output: Home page in Dark colors

$lightTheme = new LightTheme();
$aboutPage = new AboutPage($lightTheme);
echo $aboutPage->getContent();      // Output: About page in White colors
```
[Full example](Structural/Bridge.php)


## Proxy
#### The essence of the pattern
Lets you substitute special "stand-in" objects in place of real objects. These objects intercept calls to the original object, allowing you to do something before or after passing the method calls on to the original.

The proxy proposes creating a new "stand-in" class that has the same interface as the original object. We can place some intermediate logic in the proxy class, which will be executed before or after calls to the same methods on the real object. And thanks to the identical interface, the proxy object can be passed to any code that expects the original object.

#### What problems it solves
* Useful for implementing "lazy loading"
* Useful when adding extra behavior (validation, logging, caching, etc.) while preserving the interface of the original object

#### Example
```php
class WeatherProxy implements WeatherClient
{
    // ...
    public function getWeather(string $location): string
    {
        if (!isset($this->cache[$location])) {
            echo "cache: MISS\n";
            $this->cache[$location] = $this->client->getWeather($location);
        } else {
            echo "cache: HIT\n";
        }

        return $this->cache[$location];
    }
}

# Client code example
$weather = new Weather('177b4a1be7dfd10e0d30e8fdeabe0ea9');
$proxy = new WeatherProxy($weather);
echo $proxy->getWeather('Kiev');
echo $proxy->getWeather('Lviv');
echo $proxy->getWeather('Kiev');

/* Output example:
cache: MISS, weather: clear sky
cache: MISS, weather: scattered clouds
cache: HIT, weather: clear sky */
```
[Full example](Structural/Proxy.php)


## Flyweight
#### The essence of the pattern
Saves memory by sharing the common state of objects between them, instead of storing identical data in each object.

Flyweight is used in a program that has a large number of identical objects. The pattern splits the data of these objects into two parts — "flyweights" and "contexts". Now, instead of storing repeating data in all objects, individual objects will reference a few shared objects that hold this data. The client works through a factory that hides from it the complexity of organizing the shared data.

> Real-world use of the pattern in PHP is fairly rare. This is due to the single-threaded nature of PHP, where you shouldn't keep ALL of your application's objects in memory at the same time within a single thread

#### What problems it solves
* Lets you fit a larger number of objects into the allocated RAM

#### Example
```php
$shoppingCart = new ShoppingCart();
$shoppingCart->addProduct('Sports shoes', 120, 'Nike');
$shoppingCart->addProduct('Kids shoes', 100, 'Nike');
$shoppingCart->addProduct('Women shoes', 110, 'Nike');
$shoppingCart->addProduct('Running shoes', 140, 'Asics');
$shoppingCart->addProduct('Everyday shoes', 90, 'Adidas');

echo count($shoppingCart->getProducts()); // 5 products in basket
echo count(ProductFactory::$brandTypes); // and only 3 unique brands instances in memory
```
[Full example](Structural/Flyweight.php)


## 3. Behavioral Patterns

## Chain of Responsibility
#### The essence of the pattern
Lets you pass requests/calls sequentially along a chain of handlers. Each successive handler decides whether it can handle the request itself and whether the request should be passed further down the chain.
The pattern proposes linking the handler objects into a single chain. Each of them will have a reference to the next handler in the chain and will be able not only to do something with the request itself, but also to pass the handling on to the next object in the chain.

#### What problems it solves
* Run handlers sequentially one after another in the order in which they appear in the chain
* When it's not known in advance which specific requests will come in and which handlers will be needed for them

#### Example
```php
// build the chain
$logger = new DBLogger();
$logger->setNext(new MailLogger())
    ->setNext(new FileLogger());

$logger->handle($message);
/* Output:
Save to database.. 
Send by email.. 
Save to log file.. */
```
[Full example](Behavioral/ChainOfResponsibility.php)


## Command
#### The essence of the pattern
Turns operations into objects, and such objects encapsulate the action itself and its parameters. This object can now be logged, kept in a history, undone, passed to external services, and so on.

#### What problems it solves
* Turns operations into objects that can be logged, undone, added to queues, etc.
* Provides a mechanism for decoupling the client from the receiver

#### Example
```php
$invoker = new Invoker();
$receiver = new Receiver();

$invoker->pushCommand(new TurnOnCommand($receiver));
$invoker->pushCommand(new TurnOffCommand($receiver));
$invoker->execute();

/* Output:
Receiver: Turning on something..
Receiver: Turning off something.. */
```
[Full example](Behavioral/Command.php) | [Additional example](Behavioral/CommandExt.php)


## Iterator
#### The essence of the pattern
Provides the ability to sequentially traverse the elements of composite objects without exposing their internal representation. The idea of the pattern is to extract the collection-traversal behavior out of the collection itself into a separate place.

#### What problems it solves
* Lets you traverse complex data structures while hiding the details of their implementation
* Lets you have several ways of traversing the same data structure
* Lets you have a single interface for traversing different data structures
* Gives an object the ability to decide on its own how it will be iterated and what data will be available on each iteration
* Often used not only to provide access to elements, but also to endow the traversal with some additional logic

#### Example
```php
$collection = (new SimpleCollection())->addItem('1st item')
    ->addItem('2nd item')
    ->addItem('3rd item');

// go through collection in reverse order
foreach ($collection->getIterator() as $item) {
    echo $item . PHP_EOL;
}

/* Output:
3rd item
2nd item
1st item */
```
[Full example](Behavioral/Iterator.php) | [Additional example](Behavioral/IteratorExt.php)


## Mediator
#### The essence of the pattern
The mediator removes direct connections between individual components, forcing them to communicate with each other through itself.

The pattern defines an object that encapsulates the interaction logic of a certain set of other objects. The mediator provides loose coupling thanks to the fact that the objects don't reference each other explicitly, and their interaction algorithm can be changed independently. As a result, these objects are easier to reuse.

Suppose our system has many objects that interact with each other (they are often called "colleagues"). The objects can react to the actions of other objects, call various methods on each other, and in this configuration they are tightly coupled. We create a special `Mediator` object into which we move all the interaction logic of this set of objects, so that instead of contacting each other these objects will notify the mediator. This way we get rid of the tight coupling.

The objects will hold a reference to the mediator and notify it of various events. The mediator, in turn, will hold references to all the objects in this set. So, in accordance with their interaction logic, it will forward requests to specific objects.

#### What problems it solves
* Removes dependencies between the components of the system; instead they become dependent on the mediator itself
* Centralizes control in one place

#### Example
```php
$chat = new ChatMediator();

$john = new User('John', $chat);
$jane = new User('Jane', $chat);
$bot = new Bot($chat);

// every chat member interacts with mediator,
// but not with with each other directly
$john->sendMessage("Hi!");
$jane->sendMessage("What's up?");
$bot->sayHello();
```
[Full example](Behavioral/Mediator.php)


## Memento
#### The essence of the pattern
Lets you save and restore the past states of objects ("snapshots") without revealing the details of their implementation. A snapshot is a simple data object containing the state (the state of the properties) of the originator.
The pattern proposes keeping a copy of the state in a special snapshot object with a limited interface that allows, for example, finding out the snapshot's creation date or name. The pattern entrusts the creation of a copy of the object's state to the object that owns that state.

> The real-world applicability of the Memento pattern in PHP is highly questionable. Most often the task of storing a copy of the state can be solved much more simply with [serialization](http://php.net/manual/en/language.oop5.serialization.php) (using the `serialize()` and `unserialize()` calls)

#### What problems it solves
* Lets you create any number of "snapshots" of an object and store them independently of the object
* Lets you implement undo or rollback operations, for example if an operation failed

#### Example
```php
$editor = new Editor();
$editor->type('This is the first sentence.');
$editor->type('This is second.');
// make a snapshot
$memento = $editor->save();

$editor->type('And this is third.');
echo $editor->getContent();
/* Output: This is the first sentence. This is second. And this is third. */

// restore the state from snapshot
$editor->restore($memento);
echo $editor->getContent();
/* Output: This is the first sentence. This is second. */
```
[Full example](Behavioral/Memento.php)


## Observer
#### The essence of the pattern
Creates a "subscription" mechanism that lets some objects watch and react to events happening in other objects. The main participants of the pattern are the "publishers" `Subject` and the "subscribers" `Observer`.

The pattern proposes storing a list of subscriber objects inside the "publisher" object. It also provides methods by which "subscribers" can subscribe to or unsubscribe from events.

> PHP has several built-in interfaces, `SplSubject` and `SplObserver`, on top of which you can build your own implementations

#### What problems it solves
* Lets individual components react to events happening in other components
* Observers can subscribe to or unsubscribe from receiving notifications dynamically, at runtime

#### Example
```php
$cart = new Cart(); // subject
$cart->attach(new LoggingListener()); // attach an Observer
$cart->setBalance(10); // trigger an event

/* Output:
Notification: balance of the shopping cart was changed to 10 */
```
[Full example](Behavioral/Observer.php)


## Strategy
#### The essence of the pattern
Defines a family of similar algorithms and places each of them into its own class, providing the ability to interchange the algorithms at runtime.

Instead of the original class performing one algorithm or another itself, it will play the role of a context, referencing one of the strategies and delegating the work to it. To switch the algorithm, all you need to do is substitute a different strategy object into the original class.

#### What problems it solves
* Describes different ways of performing the same action, allowing these ways to be interchanged within some context object
* Lets you extract the differing behavior into a separate class hierarchy, reducing the number of if-else statements
* Lets you isolate the code, data, and dependencies of the algorithms from other objects, hiding the implementation details inside the strategy classes

#### Example
```php
interface SortStrategy
{
    public function sort(array $data): array;
}
class BubbleSortStrategy implements SortStrategy {}
class QuickSortStrategy implements SortStrategy {}

$data = [4, 2, 1, 5, 9];
// for small amount of data the "Bubble Sort" algorithm will be used
// and for large amounts - the "Quick Sort" algorithm
if (count($data) < 10) {
    $sorter = new Sorter(new BubbleSortStrategy());
    $sorter->sortArray($data);
} else {
    $sorter = new Sorter(new QuickSortStrategy());
    $sorter->sortArray($data);
}

/* Output: Sorting using bubble sort.. */
```
[Full example](Behavioral/Strategy.php)


## State
#### The essence of the pattern
Lets an object change its behavior depending on its internal state; it's an object-oriented implementation of a finite-state machine.

The pattern can be regarded as an extension of the Strategy pattern. Both patterns use composition to change the behavior of the main object, delegating the work to nested helper objects. However, in Strategy these objects know nothing about each other and are not connected in any way, whereas in State the concrete states themselves can switch the context.

#### What problems it solves
* Lets objects change their behavior depending on their state
* Lets you change behavior at runtime and get rid of conditional statements scattered throughout the code

#### Example
```php
$editor = new TextEditor(new DefaultState());
$editor->type('First line');

$editor->setState(new UpperCase());
$editor->type('Second line');

$editor->setState(new LowerCase());
$editor->type('Third line');

/* Output:
First line
SECOND LINE
third line */
```
[Full example](Behavioral/State.php)


## Template Method
#### The essence of the pattern
Lets you define the skeleton of an algorithm and lets subclasses override certain steps of the algorithm without changing its structure.

We break the algorithm down into a sequence of steps, turn these steps into methods, and call them one after another inside a single "template" method. Subclasses will be able to override certain steps, but not the actual "template" method.
We will preserve the sequence of calls, but we will have the ability to change one of these steps in the inherited classes.

#### What problems it solves
* Lets subclasses extend the base algorithm without changing its structure
* Lets you remove code duplication across several classes with similar behavior but differing in details

#### Example
```php
abstract class AbstractFileConverter
{
    // template method
    final public function convert()
    {
        $this->beforeSteps();
        $this->openFile();
        $this->validate();
        $this->makeConversion();
        $this->closeFile();
        $this->afterSteps();
    }
}
// ...

(new PDFFileConverter())->convert();
/* Output:
Step1. Read from file..
Step2. Validate PDF file..
Step3. Convert PDF file..
Step4. Close a file descriptor.. */

(new CSVFileConverter())->convert();
/* Output:
Step1. Read from file..
Step2. Validate CSV file..
Step3. Convert CSV file..
Step4. Close a file descriptor.. */
```
[Full example](Behavioral/TemplateMethod.php) | [Additional example](Behavioral/TemplateMethodExt.php)


## Visitor
#### The essence of the pattern
Lets you extend a set of objects (not necessarily related to each other) with new functions. The function usually has a common meaning or a single purpose for all objects of these classes, but is implemented differently for each of them (for example, exporting an entity).

In other words, it lets you add new operations without changing the classes of the objects on which these operations can be performed. When the visitor is changed, there's no need to change the main classes.

The "Double Dispatch" approach is used, where the concrete implementation of the method that will be called at runtime depends both on the object on which the method is called and on the type of the object passed as an argument.

> You will still have to change the node classes once. It's important that the hierarchy of components changes rarely, because when adding a new component you'll have to change all the existing visitors

#### What problems it solves
* Gives you the ability to introduce new behavior into objects without making changes to the classes
* Lets you introduce functionality when you don't have access to or the ability to change the classes, or don't want to give them additional responsibility

#### Example
```php
$report = new Report('report_title', 'report_content');

echo $report->accept(new JSONExportVisitor());
echo $report->accept(new XMLExportVisitor());

/* Output:
"report_title {diagram} report_content"
<?xml version="1.0" <xml><title>report_title<diagram> */
```
[Full example](Behavioral/Visitor.php)
