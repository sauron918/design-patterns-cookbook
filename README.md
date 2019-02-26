# Design Patterns Cookbook
Коллекция самых популярных Шаблонов Проектирования (Design Patterns) с описанием и примерами на PHP.


## Порождающие шаблоны (Creational Patterns)

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

/*  Next calls will produce errors:
    $app = new Application();
    $app = clone $app;
    $app = unserialize(serialize($app)); */
```
[Полный пример](Creational/Singleton.php)


## Прототип (Prototype)
#### Суть паттерна
Паттерн описывает процесс создания объектов-клонов на основе имеющегося объекта-прототипа. Другими словами, паттерн Prototype описывает способ организации процесса клонирования.

Обычно операция клонирования происходить через метод `getClone()`, который описан в базовом классе. Но озможность клонирования объектов [встроена в PHP](http://php.net/manual/ru/language.oop5.cloning.php) . При помощи ключевого слова `clone` вы можете сделать точную копию объекта. Чтобы добавить поддержку клонирования в класс, необходимо реализовать метод `__clone`.

#### Какие проблемы решает
* Позволяет копировать объекты, не вдаваясь в подробности их реализации.

#### Пример
```php
class Page
{
    // ...
    public function getClone()
    {
        return new static($this->title);
    }
}

$page = new Page('Page Title');
echo $pageClone = $page->getClone(); // Page Title
```
[Полный пример](Creational/Singleton.php) | [Дополнительный пример](Creational/PrototypeExt.php)


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
[Полный пример](Creational/Singleton.php) | [Дополнительный пример](Creational/PrototypeExt.php)


## Фабричный метод (Factory Method)
#### Суть паттерна
Определяет общий интерфейс для создания объектов в суперклассе, позволяя подклассам изменять тип создаваемых объектов. 
Паттерн Фабричный метод предлагает создавать объекты не напрямую, используя оператор `new`, а через вызов особого фабричного метода. Объекты всё равно будут создаваться при помощи new, но делать это будет фабричный метод. Чтобы эта система заработала, все возвращаемые объекты должны иметь общий интерфейс. 

#### Какие проблемы решает
* Позволяет использовать наследование полиморфизм, чтобы инкапсулировать создание конкретных продуктов. Принцип полиморфизма применяется к генерации объектов
* Решает проблему создания различных объектов, без указания конкретных классов
* Применяется когда система должна оставаться легко расширяемой, путем добавления объектов новых типов

#### Пример
```php
$data = 'some input data';
$responseFormat = 'json'; // taken from configuration for example

switch ($responseFormat) {
    case 'json':
        $response = new JsonResponse($data); // use JSONFormatter
        break;
    case 'html':
        $response = new HTMLResponse($data); // use HTMLFormatter
        break;
}

echo $response;
/* Output: {"code": 200, "response": "some input data"} */
```
[Полный пример](Creational/FactoryMethod.php)


## Абстрактная фабрика (Abstract Factory)
#### Суть паттерна
Позволяет создавать семейства связанных объектов, не привязываясь к конкретным классам создаваемых объектов. Или другими словами - предусматривает интерфейс для создания семейства связанных или зависимых объектов бек указания конкретных классов.
Обычно программа создаёт конкретный объект фабрики при запуске, причём тип фабрики выбирается, исходя из параметров окружения или конфигурации.

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

# Структурные шаблоны (Structural Patterns)

## Адаптер (Adapter)
#### Суть паттерна
Адаптирует существующий код к требуемому интерфейсу (является переходником). Например, у вас есть класс и его интерфейс не совместим с кодом вашей системы, в этом случае мы не изменяем код этого класса, а пишем для него адаптер. Другими словами "оборачиваем" объект так, чтобы хранить ссылку на него, и непосредственно конвертируем интерфейс объекта к требуемому. 
Мы можем создавать адаптер в любом направлении, как для какой-то старой системы чтобы использовать ее функционал с новым интерфейсом, так и любой новый интерфейс в соответствии с тем что ожидает уже существующий объект.

#### Какие проблемы решает
* Позволяет использовать сторонний класс, если его интерфейс не совместим с существующим кодом.
* Когда нужно использовать несколько существующих подклассов, но в них не хватает какой-то общей функциональности (и расширить суперкласс мы не можем).

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
* Позволяя добавлять другие декораторы "в цепочке"
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
* Помогает произвести декомпозицию сложной подсистемы на "слои"

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

Паттерн Мост предлагает заменить наследование делегированием. Когда класс нужно расширять в двух независимых плоскостях, паттерн предлагает выделить одну из таких плоскостей в отдельную иерархию классов, храня ссылку на один из её объектов в первоначальном классе.

#### Какие проблемы решает
* Полезен в ситуации, когда класс нужно расширять в двух независимых плоскостях
* Позволяет разделить монолитный класс, который содержит несколько различных реализаций на более специализированные реализации
* Сохраняет возможность подмены реализации во время выполнения программы

#### Пример
```php
// some abstraction hierarchy
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

// separate implementation hierarchy
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

Заместитель предлагает создать новый класс "дублер", имеющий тот же интерфейс, что и оригинальный объект. Мы можем поместить в класс заместителя какую-то промежуточную логику, которая будет  выполняться до или после вызовов этих же методов в настоящем объекте. А благодаря одинаковому интерфейсу, объект-заместитель можно передать в любой код, ожидающий оригинальный объект.

#### Какие проблемы решает
* Полезен в реализации "ленивой загрузки"
* Полезен добавлении дополнительного поведения, например: проверок, логирования, кэширования и т.д. сохраняя при этом интерфейс оригинального объекта

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

Легковес применяется в программе, имеющей большое количество одинаковых объектов. Паттерн разделяет данные этих объектов на две части — легковесы и контексты. Теперь, вместо хранения повторяющихся данных во всех объектах, отдельные объекты будут ссылаться на несколько общих объектов, хранящих эти данные. Клиент работает с деревьями через фабрику деревьев, которая скрывает от него сложность организации общих данных деревьев.

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


# Поведенческие шаблоны (Behavioral Patterns)

Soon..