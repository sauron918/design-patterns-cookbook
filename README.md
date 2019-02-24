# Design Patterns Cookbook
Коллекция самых популярных Шаблонов Проектирования (Design Patterns) с описанием и примерами на PHP.


## Порождающие шаблоны (Creational Patterns)

### Одиночка (Singleton)
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


### Прототип (Prototype)
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


### Строитель (Builder)
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


### Фабричный метод (Factory Method)
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


### Абстрактная фабрика (Abstract Factory)
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
echo ($templateFactory->createHeader())->render();
echo ($templateFactory->createBody())->render();
/* Output: <h1>{{ $title }}</h1><main>{{ $content }}</main> */
```
[Полный пример](Creational/AbstractFactory.php) 

