<?php

namespace DesignPatterns\Structural;

/**
 * Declares common operations for both Subject and the Proxy
 */
interface WeatherClient
{
    public function getWeather(string $location): string;
}

/**
 * Third party weather API client (which we probably can't modify)
 * contains some core business logic
 */
class Weather implements WeatherClient
{
    protected $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getWeather(string $location): string
    {
        $json = file_get_contents('http://api.openweathermap.org/data/2.5/weather?q=' . $location
            . '&APPID=' . $this->token);
        $data = json_decode($json, true);

        return 'weather: ' . $data['weather'][0]['description'] . PHP_EOL ?? 'no data';
    }
}

/**
 * Proxy has an interface identical to the Subject and allows us caching the results
 */
class WeatherProxy implements WeatherClient
{
    /** @var WeatherClient Real third party client */
    protected $client;
    private $cache = [];

    public function __construct(WeatherClient $client)
    {
        $this->client = $client;
    }

    public function getWeather(string $location): string
    {
        if (!isset($this->cache[$location])) {
            echo "- cache: MISS\n";
            $this->cache[$location] = $this->client->getWeather($location);
        } else {
            echo "+ cache: HIT, retrieving result from cache..\n";
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
- cache: MISS
weather: clear sky
- cache: MISS
weather: scattered clouds
+ cache: HIT, retrieving result from cache..
weather: clear sky */