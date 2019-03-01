# Design Patterns Cookbook
Коллекция самых популярных Шаблонов Проектирования (Design Patterns) с описанием и примерами на PHP.


## 1. Порождающие шаблоны (Creational Patterns)

## Одиночка (Singleton)
#### Суть паттерна
Гарантирует, что существует только один объект данного типа в приложении, и предоставляет к нему глобальную точку доступа.

#### Какие проблемы решает
* Гарантирует наличие единственного экземпляра класса
* Предоставляет глобальную точку доступа

#### Пример
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
[Полный пример](Creational/Singleton.php)


## Прототип (Prototype)
#### Суть паттерна
Паттерн описывает процесс создания объектов-клонов на основе имеющегося объекта-прототипа. Другими словами, паттерн  описывает способ организации процесса клонирования. Обычно операция клонирования происходить через метод `getClone()`, который описан в базовом классе. 

В PHP возможность клонирования объектов [встроена](http://php.net/manual/en/language.oop5.cloning.php), при помощи ключевого слова `clone` вы можете сделать точную копию объекта. Чтобы добавить поддержку клонирования в класс, необходимо реализовать метод `__clone()`.

#### Какие проблемы решает
* Позволяет копировать объекты, не вдаваясь в подробности их реализации

#### Пример
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
[Полный пример](Creational/Prototype.php) | [Дополнительный пример](Creational/PrototypeExt.php)


## Строитель (Builder)
#### Суть паттерна
Позволяет создавать сложные объекты пошагово, а также дает возможность использовать один и тот же код строительства для получения разных представлений объектов.

#### Какие проблемы решает
* Определяет процесс поэтапного построения сложного продукта
* Позволяет избавиться от "телескопических" конструкторов `__construct($param1, $param2, ..., $paramN)`

#### Пример
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
[Полный пример](Creational/Builder.php) | [Дополнительный пример](Creational/BuilderExt.php)


## Фабричный метод (Factory Method)
#### Суть паттерна
Определяет общий интерфейс для создания объектов в суперклассе, позволяя подклассам изменять тип создаваемых объектов. 
Предлагает создавать объекты не напрямую, используя оператор `new`, а через вызов особого фабричного метода. Объекты все равно будут создаваться при помощи new, но делать это будет фабричный метод. Чтобы эта система заработала, все возвращаемые объекты должны иметь общий интерфейс. 

#### Какие проблемы решает
* Позволяет использовать наследование и полиморфизм, чтобы инкапсулировать создание конкретных экземпляров
* Решает проблему создания объектов, без указания конкретных классов
* Применяется когда система должна оставаться легко расширяемой, путем добавления объектов новых типов

#### Пример
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
[Полный пример](Creational/FactoryMethod.php)


## Абстрактная фабрика (Abstract Factory)
#### Суть паттерна
Позволяет создавать семейства связанных объектов, не привязываясь к конкретным классам создаваемых объектов. Или другими словами - предусматривает интерфейс для создания семейства связанных или зависимых объектов без указания конкретных классов.
Обычно программа создает конкретный объект фабрики при запуске, причем тип фабрики выбирается, исходя из параметров окружения или конфигурации.

#### Какие проблемы решает
* Скрывает от клиентского кода подробности того, как и какие конкретно объекты будут созданы
* Решает проблему несовместимости набора связанных объектов, при их создании

#### Пример
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
[Полный пример](Creational/AbstractFactory.php) 


## 2. Структурные шаблоны (Structural Patterns)

## Адаптер (Adapter)
#### Суть паттерна
Адаптирует существующий код к требуемому интерфейсу (является переходником). Например, у вас есть класс и его интерфейс не совместим с кодом вашей системы, в этом случае мы не изменяем код этого класса, а пишем для него адаптер - "оборачиваем" объект так, чтобы хранить ссылку на него, и непосредственно конвертируем интерфейс объекта к требуемому. 
Мы можем создавать адаптер в любом направлении, как для какой-то старой системы чтобы использовать ее функционал с новым интерфейсом, так и любой новый интерфейс в соответствии с тем что ожидает уже существующий объект.

#### Какие проблемы решает
* Позволяет использовать сторонний класс, если его интерфейс не совместим с существующим кодом
* Когда нужно использовать несколько существующих подклассов, но в них не хватает какой-то общей функциональности (и расширить суперкласс мы не можем)

#### Пример
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
[Полный пример](Structural/Adapter.php)


## Декоратор (Decorator)
#### Суть паттерна
Позволяет динамически добавлять объектам новую функциональность, не изменяя их интерфейс.

#### Какие проблемы решает
* Помогает расширить класс каким-то определенным действием, не изменяя интерфейс
* Позволяет добавлять другие декораторы "в цепочке"
* Применяется когда нельзя расширить обязанности объекта с помощью наследования

#### Пример
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
[Полный пример](Structural/Decorator.php)


## Фасад (Facade)
#### Суть паттерна
Предоставляет упрощенный интерфейс к сложной системе вызовов или системе классов.

#### Какие проблемы решает
* Представляет простой или урезанный интерфейс к сложной подсистеме
* Помогает произвести декомпозицию сложной подсистемы на "подсистемы"

#### Пример
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
[Полный пример](Structural/Facade.php)


## Компоновщик (Composite)
#### Суть паттерна
Позволяет сгруппировать множество объектов в древовидную структуру, а затем работать с ней так, как будто это единичный объект. Паттерн предлагает хранить в составных объектах ссылки на другие простые или составные объекты. Те, в свою очередь, тоже могут хранить свои вложенные объекты и так далее.

Клиентский код работает со всеми объектами через общий интерфейс и не знает, что перед ним — простой или составной объект. Это позволяет клиентскому коду работать с деревьями объектов любой сложности, не привязываясь к конкретным классам объектов, формирующих дерево.

#### Какие проблемы решает
* Упрощает работу с любыми древовидными рекурсивными структурами
* Позволяет единообразно трактовать простые и составные объекты

#### Пример
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
[Полный пример](Structural/Composite.php)


## Мост (Bridge)
#### Суть паттерна
Разделяет один или несколько классов на две отдельные иерархии — абстракцию и реализацию, позволяя изменять их независимо друг от друга.

Паттерн предлагает заменить наследование делегированием. Когда класс нужно расширять в двух независимых плоскостях, паттерн предлагает выделить одну из таких плоскостей в отдельную иерархию классов, храня ссылку на один из ее объектов в первоначальном классе.

#### Какие проблемы решает
* Полезен в ситуации, когда класс нужно расширять в двух независимых плоскостях
* Позволяет разделить монолитный класс, который содержит несколько различных реализаций на более специализированные реализации
* Сохраняет возможность подмены реализации во время выполнения программы

#### Пример
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
[Полный пример](Structural/Bridge.php)


## Заместитель (Proxy)
#### Суть паттерна
Позволяет подставлять вместо реальных объектов специальные объекты "заменители". Эти объекты перехватывают вызовы к оригинальному объекту, позволяя сделать что-то до или после передачи вызова методов оригинала.

Заместитель предлагает создать новый класс "дублер", имеющий тот же интерфейс, что и оригинальный объект. Мы можем поместить в класс заместителя какую-то промежуточную логику, которая будет выполняться до или после вызовов этих же методов в настоящем объекте. А благодаря одинаковому интерфейсу, объект-заместитель можно передать в любой код, ожидающий оригинальный объект.

#### Какие проблемы решает
* Полезен в реализации "ленивой загрузки"
* Полезен при добавлении дополнительного поведения (проверок, логирования, кэширования и т.д.), сохраняет при этом интерфейс оригинального объекта

#### Пример
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
[Полный пример](Structural/Proxy.php)


## Легковес (Flyweight)
#### Суть паттерна
Экономит память, разделяя общее состояние объектов между собой, вместо хранения одинаковых данных в каждом объекте.

Легковес применяется в программе, имеющей большое количество одинаковых объектов. Паттерн разделяет данные этих объектов на две части — "легковесы" и "контексты". Теперь, вместо хранения повторяющихся данных во всех объектах, отдельные объекты будут ссылаться на несколько общих объектов, хранящих эти данные. Клиент работает через фабрику, которая скрывает от него сложность организации общих данных.

> Реальное применение паттерна на PHP встречается довольно редко. Это связано с однопоточным характером PHP, где вы не должны хранить ВСЕ объекты вашего приложения в памяти одновременно в одном потоке

#### Какие проблемы решает
* Позволяет вместить большее количество объектов в отведенную оперативную память

#### Пример
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
[Полный пример](Structural/Flyweight.php)


## 3. Поведенческие шаблоны (Behavioral Patterns)

## Цепочка обязанностей (Chain of Responsibility)
#### Суть паттерна
Позволяет передавать запросы/вызовы последовательно по цепочке обработчиков. Каждый последующий обработчик решает, может ли он обработать запрос сам и стоит ли передавать запрос дальше по цепочке.
Паттерн предлагает связать объекты обработчиков в одну цепь. Каждый из них будет иметь ссылку на следующий обработчик в цепи и сможет не только сам что-то с ним сделать, но и передать обработку следующему объекту в цепочке. 

#### Какие проблемы решает
* Запускать обработчиков последовательно один за другим в том порядке, в котором они находятся в цепочке
* Когда заранее неизвестно, какие конкретно запросы будут приходить и какие обработчики для них понадобятся

#### Пример
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
[Полный пример](Behavioral/ChainOfResponsibility.php)


## Команда (Command)
#### Суть паттерна
Превращает операции в объекты, и такие объекты заключают в себя само действие и его параметры. Этот объект теперь можно логировать, хранить историю, отменять, передавать во внешние сервисы и так далее.

#### Какие проблемы решает
* Превращает операции в объекты, которые можно логировать, отменять, добавлять в очереди и т.д.
* Предоставляет механизм отделения клиента от получателя

#### Пример
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
[Полный пример](Behavioral/Command.php) | [Дополнительный пример](Behavioral/CommandExt.php)


## Итератор (Iterator)
#### Суть паттерна
Предоставляет возможность последовательно обходить элементы составных объектов, не раскрывая их внутреннего представления. Идея паттерна состоит в том, чтобы вынести поведение обхода коллекции из самой коллекции отдельно.

#### Какие проблемы решает
* Позволяет обходить сложные структуры данных, и скрыть при этом детали ее реализации
* Позволяет иметь несколько вариантов обхода одной и той же структуры данных
* Позволяет иметь единый интерфейс обхода различных структур данных
* Дает возможность объекту самостоятельно принимать решение, как он будет итерироваться и какие данные будут доступны на каждой итерации
* Зачастую используется, чтобы не только предоставить доступ к элементам, но и наделить обход некоторой дополнительной логикой

#### Пример
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
[Полный пример](Behavioral/Iterator.php) | [Дополнительный пример](Behavioral/IteratorExt.php)


## Посредник(Mediator)
#### Суть паттерна
Посредник убирает прямые связи между отдельными компонентами, заставляя их общаться друг с другом через себя.

Паттерн определяет объект, который инкапсулирует логику взаимодействия некоторого набора других объектов. Посредник обеспечивает слабую связность благодаря тому, что объекты не ссылаются друг на друга явно и можно изменять алгоритм их взаимодействия независимо. Таким образом эти объекты проще переиспользовать. 

Допустим в нашей системе есть множество объектов которые взаимодействуют друг с другом (часто их называют "коллегами"). Объекты могут реагировать на действие других объектов, вызывать друг у друга различные методы, и в данной конфигурации они являются сильно связанными. Мы создаем специальный объект `Mediator`, в который перенесем всю логику взаимодействия этого набора объектов, так что эти объекты вместо обращения друг другу будут уведомлять посредника. Тем самым мы избавимся от сильной связности. 

Объекты будут иметь ссылку на посредника и уведомлять его при различных событиях. А посредник свою очередь будет иметь ссылки на все объекты из этого множества. Так, что в соответствии с логикой их взаимодействия будет перенаправлять запросы конкретным объект.

#### Какие проблемы решает
* Убирает зависимости между компонентами системы, вместо этого они становятся зависимыми от самого посредника
* Централизует управление в одном месте

#### Пример
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
[Полный пример](Behavioral/Mediator.php)


## Снимок (Memento)
#### Суть паттерна
Позволяет сохранять и восстанавливать прошлые состояния объектов "снимки", не раскрывая подробностей их реализации. Снимок — это простой объект данных, содержащий состояние (состояние свойств) создателя. 
Паттерн предлагает держать копию состояния в специальном объекте-снимке с ограниченным интерфейсом, позволяющим, например, узнать дату изготовления или название снимка. Паттерн поручает создание копии состояния объекта самому объекту, который этим состоянием владеет.

> Реальная применимость паттерна Снимок в PHP под большим вопросом. Чаще всего задачу хранения копии состояния можно решить куда проще при помощи [сериализации](http://php.net/manual/en/language.oop5.serialization.php) (применения вызовов `serialize()` и `unserialize()`)

#### Какие проблемы решает
* Позволяет создавать любое количество "снимков" объекта и хранить их независимо от объекта
* Позволяет реализовать операции отмены или отката состояния, например если операция не удалась

#### Пример
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
[Полный пример](Behavioral/Memento.php)


## Наблюдатель (Observer)
#### Суть паттерна
Создает механизм "подписки", позволяющий одним объектам следить и реагировать на события, происходящие в других объектах. Основные участники паттерна это "издатели" `Subject` и "подписчики" `Observer`.

Паттерн предлагает хранить внутри объекта "издателя" список объектов подписчиков. А также предоставлять методы, с помощью которых "подписчики" могут подписаться или отписаться на события.

> PHP имеет несколько встроенных интерфейсов `SplSubject`, `SplObserver`, на основе которых можно строить свои реализации

#### Какие проблемы решает
* Позволяет отдельным компонентам реагировать на события, происходящие в других компонентах
* Наблюдатели могут подписываться или отписываться от получения оповещений динамически, во время выполнения программы

#### Пример
```php
$cart = new Cart(); // subject
$cart->attach(new LoggingListener()); // attach an Observer
$cart->setBalance(10); // trigger an event

/* Output:
Notification: balance of the shopping cart was changed to 10 */
```
[Полный пример](Behavioral/Observer.php)


## Стратегия (Strategy)
#### Суть паттерна
Определяет семейство схожих алгоритмов и помещает каждый из них в собственный класс, предоставляет возможность взаимозаменять алгоритмы во время исполнения программы.

Вместо того, чтобы изначальный класс сам выполнял тот или иной алгоритм, он будет играть роль контекста, ссылаясь на одну из стратегий и делегируя ей выполнение работы. Чтобы сменить алгоритм, вам будет достаточно подставить в изначальный класс другой объект-стратегию.

#### Какие проблемы решает
* Описывает разные способы произвести одно и то же действие, позволяя взаимозаменять эти способы в каком-то объекте контекста
* Позволяет вынести отличающееся поведение в отдельную иерархию классов, уменьшает количество if-else операторов
* Позволяет изолировать код, данные и зависимости алгоритмов от других объектов, скрыв детали реализации внутри классов-стратегий

#### Пример
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
[Полный пример](Behavioral/Strategy.php)


## Состояние (State)
#### Суть паттерна
Позволяет объекту изменять свое поведение в зависимости от внутреннего состояния, является объектно-ориентированной реализацией конечного автомата.

Шаблон можно рассматривать как надстройку над шаблоном Стратегия. Оба паттерна используют композицию, чтобы менять поведение основного объекта, делегируя работу вложенным объектам-помощникам. Однако в Стратегии эти объекты не знают друг о друге и никак не связаны, тогда как в Состоянии сами конкретные состояния могут переключать контекст.

#### Какие проблемы решает
* Позволяет объектам менять поведение в зависимости от своего состояния
* Позволяет изменять поведение во время выполнения программы и избавиться от условных операторов, разбросанных по коду

#### Пример
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
[Полный пример](Behavioral/State.php)


## Шаблонный метод (Template method)
#### Суть паттерна
Позволяет определить каркас алгоритма и позволяет подклассам переопределять определенные этапы алгоритма без изменения его структуры.

Мы разбиваем алгоритм на последовательность шагов, превращаем эти шаги в методы и вызываем их один за другим внутри одного "шаблонного" метода. Подклассы смогут переопределять определенные шаги, но не фактический метод "шаблона".
Мы сохраним последовательность вызовов, но у нас будет возможность изменить один из этих шагов в унаследованных классах.

#### Какие проблемы решает
* Позволяет подклассам расширять базовый алгоритм, не меняя его структуры
* Позволяет убрать дублирование кода в нескольких классах с похожим поведением, но отличающихся в деталях

#### Пример
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
[Полный пример](Behavioral/TemplateMethod.php) | [Дополнительный пример](Behavioral/TemplateMethodExt.php)


## Посетитель (Visitor)
#### Суть паттерна
Позволяет расширить набор объектов (не обязательно связанных между собой) новыми функциями. Функция, как правило, имеет общий смысл или одну цель для всех объектов этих классов, но реализуется для каждого из них по разному (например экспорт сущности).

Другими словами, позволяет добавлять новые операции, не меняя классы объектов, над которыми эти операции могут выполняться. При изменении посетителя нет необходимости изменять основные классы. 

Применяется подход "двойной диспетчеризации" (Double Dispatch), когда конкретная реализация метода, который будет вызван при работе программы, зависит и от объекта у которого этот метод вызывается и от типа объекта который передается в качестве аргумента.

> Изменить классы узлов единожды все-таки придется. Важно, чтобы иерархия компонентов, менялась редко, так как при добавлении нового компонента придется менять всех существующих посетителей

#### Какие проблемы решает
* Дает возможность внедрять новое поведение в объекты, без внесения изменений в классы
* Позволяет внедрить функциональность когда нет доступа или возможности изменять классы или не хочется добавлять им дополнительную ответственность

#### Пример
```php
$report = new Report('report_title', 'report_content');

echo $report->accept(new JSONExportVisitor());
echo $report->accept(new XMLExportVisitor());

/* Output:
"report_title {diagram} report_content"
<?xml version="1.0" <xml><title>report_title<diagram> */
```
[Полный пример](Behavioral/Visitor.php)

