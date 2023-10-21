<?php
namespace Eddy\Crawlers;

use Eddy\Crawlers\PluginBoutique\Crawler;
use Eddy\Crawlers\PluginBoutique\URL;
use Eddy\Crawlers\Shared\ClientFactory;
use Eddy\Robots\{Robots, Factory};
use GuzzleHttp\Client as Guzzle;

/**
 * Plugin Boutique crawler cheeky god object
 * 
 * @property URL $url
 * @property Guzzle $guzzle
 * @property Guzzle $client
 * @property Robots $robots
 * @property Crawler $crawler
 */
class PB
{
    private URL $pbURL;

    private Robots $robotsObject;

    private Crawler $domCrawler;

    public function __construct(private ?Guzzle $guzzle = null)
    {
        $this->pbURL = new URL();
        if (!$guzzle) $this->guzzle = ClientFactory::pluginBoutique();
        $this->initRobots();
        $this->initCrawler();
    }

    private function initCrawler()
    {
        $this->domCrawler = new Crawler();
    }

    private function initRobots()
    {
        $url = URL::robots();

        $res = $this->guzzle->request('GET', $url);
        if ($res->getStatusCode() !== 200) {
            throw new \RuntimeException(
                'Could not locate ' . Robots::TXT
            );
        }
        $body = $res->getBody()->getContents();
        $this->robotsObject = Factory::make($body);
    }

    public function client(): Guzzle
    {
        return $this->guzzle;
    }

    public function url(): URL
    {
        return $this->pbURL;
    }

    public function crawler()
    {
        return $this->domCrawler;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->guzzle, $name)) {
            return call_user_func([$this->guzzle, $name], ...$arguments);
        }

        throw new \BadMethodCallException(
            'Undefined method: ' . $name
        );
    }

    public function __get($name)
    {
        if ($name === 'url') return $this->pbURL;
        if ($name === 'robots') return $this->robotsObject;
        if ($name === 'crawler') return $this->domCrawler;
        if ($name === 'client' || $name === 'guzzle') return $this->guzzle;

        throw new \OutOfBoundsException(
            'Undefined property: ' . $name
        );
    }
}
