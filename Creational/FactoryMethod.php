<?php

namespace DesignPatterns\Creational;

/**
 * Base factory class contains a factory method and some business logic
 */
abstract class Response
{
    protected $data;

    public function __construct(string $data)
    {
        $formatter = $this->createFormatter();
        $this->data = $formatter->wrapData($data);
    }

    /**
     * Factory Method, usually abstract
     * but it can also return a certain standard product (formatter)
     */
    abstract function createFormatter(): Formatter;

    public function __toString()
    {
        return $this->data;
    }
}

/**
 * Concrete factories extend that method to produce different kinds of response
 */
class JSONResponse extends Response
{
    public function createFormatter(): Formatter
    {
        return new JSONFormatter();
    }
}

class HTMLResponse extends Response
{
    public function createFormatter(): Formatter
    {
        return new HTMLFormatter();
    }
}

/**
 * Formatter interface declares behaviors of various types of response
 */
interface Formatter
{
    public function wrapData(string $data): string;
}

class HTMLFormatter implements Formatter
{
    public function wrapData(string $data): string
    {
        return '<html>' . $data . '</html>';

    }
}

class JSONFormatter implements Formatter
{
    public function wrapData(string $data): string
    {
        return '{"code": 200, "response": "' . $data . '"}';
    }
}

# Client code example
$data = 'some input data';
$responseFormat = 'json'; // taken from configuration for example
$response = '';

switch ($responseFormat) {
    case 'json':
        $response = new JsonResponse($data);
        break;
    case 'html':
        $response = new HTMLResponse($data);
        break;
}

echo $response;
/* Output: {"code": 200, "response": "some input data"} */