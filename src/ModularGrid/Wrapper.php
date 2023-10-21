<?php
namespace Eddy\Crawlers\ModularGrid;

use Eddy\Crawlers\ModularGrid\Crawler\Crawler;
use Eddy\Crawlers\ModularGrid\Util\URL;
use Eddy\Crawlers\Shared\ClientFactory;
use Eddy\Crawlers\Shared\Robots;
use GuzzleHttp\Client as Guzzle;
// use GuzzleHttp\HandlerStack;
// use Illuminate\Support\Facades\Cache;
// use Kevinrob\GuzzleCache\CacheMiddleware;
// use Kevinrob\GuzzleCache\Storage\LaravelCacheStorage;
// use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
/**
 * Wrapper class for basic functionality
 * 
 * @property URL $url URL factory
 * @property Guzzle $guzzle
 * @property Guzzle $client
 * @property int $delay
 */
class Wrapper
{
    private Robots $bots;

    /**
     * Crawl delay in seconds
     *
     * @var int
     */
    private int $crawlDelay;

    public function __construct(private ?Guzzle $guzzle = null)
    {
        if (!$guzzle) $this->initGuzzle();
        $this->initBots();
    }

    private function initGuzzle()
    {
        // $stack = HandlerStack::create();
        // $stack->push(new CacheMiddleware(
        //     new PrivateCacheStrategy(
        //         new LaravelCacheStorage(Cache::store('redis'))
        //     )
        // ), 'cache');
        $this->guzzle = ClientFactory::modularGrid();
    }

    private function initBots()
    {
        $res = $this->client->request('GET', $this->url->robots);
        if ($res->getStatusCode() === 404) {
            return null;
        }
        $body = $res->getBody()->getContents();

        $this->bots = new Robots($body);
        $cd = $this->bots->crawlDelay();
        if (!$cd) $cd = $this->bots->crawlDelay(Robots::clientAgent());
        $this->crawlDelay = $cd ?? 3;
    }

    public function client()
    {
        if (!isset($this->guzzle)) $this->initGuzzle();
        return $this->guzzle;
    }
    
    public function crawl(string $html)
    {
        return new Crawler($html);
    }

    public function requestConfig()
    {
        return [
            'connect_timeout' => 5,
            'http_errors' => false,
            'delay' => $this->crawlDelay * 1000
        ];
    }

    public function request(string $method, string $url = '', array $options = [])
    {
        return $this->client->request($method, $url, [
            ...$this->requestConfig(),
            ...$options
        ]);
    }

    public function ping(string $url, bool $dataOnSuccess = true)
    {
        $res = $this->request('GET', $url);
        $status = $res->getStatusCode();
        $result = $status === 200 || $status === 300;
        if ($result && $dataOnSuccess) {
            return $res->getBody()->getContents();
        }
        return $result;
    }

    public function robots()
    {
        return $this->bots;
    }

    public function __get($name)
    {
        if ($name === 'url') return new URL();
        if ($name === 'guzzle' || $name === 'client') return $this->client();
        if ($name === 'delay') return $this->crawlDelay;

        throw new \OutOfRangeException('Undefined property: ' . $name);
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->guzzle, $name)) {
            return call_user_func_array([$this->guzzle, $name], $arguments);
        }

        throw new \BadMethodCallException(
            'Undefined method: ' . $name
        );
    }
}
