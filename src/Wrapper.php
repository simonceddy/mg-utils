<?php
namespace Eddy\ModularGrid;

use Eddy\ModularGrid\Crawler\Crawler;
use Eddy\ModularGrid\Util\URL;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Facades\Cache;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\LaravelCacheStorage;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
/**
 * Wrapper class for basic functionality
 * 
 * @property URL $url URL factory
 * @property Guzzle $guzzle
 * @property Guzzle $client
 */
class Wrapper
{
    public function __construct(private ?Guzzle $guzzle = null)
    {
        // if (!$guzzle) $this->guzzle = new Guzzle();
    }

    private function initGuzzle()
    {
        $stack = HandlerStack::create();
        $stack->push(new CacheMiddleware(
            new PrivateCacheStrategy(
                new LaravelCacheStorage(Cache::store('redis'))
            )
        ), 'cache');
        $this->guzzle = new Guzzle([
            'base_uri' => URL::BASE,
            'handler' => $stack,
        ]);
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

    public function ping(string $url, bool $dataOnSuccess = true)
    {
        $res = $this->client()->request('GET', $url, [
            'connect_timeout' => 5,
            'http_errors' => false,
        ]);
        $status = $res->getStatusCode();
        $result = $status === 200 || $status === 300;
        if ($result && $dataOnSuccess) {
            return $res->getBody()->getContents();
        }
        return $result;
    }

    public function __get($name)
    {
        if ($name === 'url') return new URL();
        if ($name === 'guzzle' || $name === 'client') return $this->client();

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
