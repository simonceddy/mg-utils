<?php
namespace Eddy\Crawlers;

use Eddy\Crawlers\PedalEmpire\Crawler;
use Eddy\Crawlers\PedalEmpire\PedalEmpireURL;
use Eddy\Crawlers\Shared\ClientFactory;
use Eddy\Crawlers\Shared\Robots;
use GuzzleHttp\Client as Guzzle;
use Symfony\Component\DomCrawler\Crawler as SymCrawler;

/**
 * Cheeky god object for Pedal Empire crawlers
 * 
 * @property PedalEmpireURL $url
 * @property Guzzle $client
 * @property Guzzle $guzzle
 * @property Crawler $crawler
 * @property Crawler $crawl
 */
class PE
{
    private PedalEmpireURL $peUrl;

    private int $crawlDelay;

    private Robots $bots;

    public function __construct(
        private ?Guzzle $client = null,
        private ?SymCrawler $symCrawler = null,
        // private ?Robots $bots = null,
    ) {
        $this->peUrl = new PedalEmpireURL();
        if (!$client) $this->client = ClientFactory::pedalEmpire();
        if (!$symCrawler) $this->symCrawler = new SymCrawler();
        /* if (!$bots)  */$this->fetchRobots();
    }

    private function fetchRobots()
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

    public function robots()
    {
        return $this->bots;
    }

    public function url()
    {
        return $this->peUrl;
    }

    public function crawl(?string $html = null)
    {
        if ($html) {
            $this->symCrawler->clear();
            $this->symCrawler->addContent($html);
        }
        return new Crawler($this->symCrawler);
    }

    public function requestConfig()
    {
        return [
            'connect_timeout' => 5,
            'http_errors' => false,
            'delay' => $this->crawlDelay
        ];
    }

    public function request(string $method, string $url = '', array $options = [])
    {
        return $this->client->request($method, $url, [
            ...$this->requestConfig(),
            ...$options
        ]);
    }

    public function __get($name)
    {
        if ($name === 'url') {
            return $this->peUrl;
        }
        if ($name === 'client' || $name === 'guzzle') {
            return $this->client;
        }
        if ($name === 'crawler' || $name === 'crawl') {
            return $this->crawl();
        }

        throw new \OutOfBoundsException(
            'Undefined property: ' . $name
        );
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->client, $name)) {
            return call_user_func_array([$this->client, $name], $arguments);
        }

        throw new \BadMethodCallException(
            'Undefined method: ' . $name
        );
    }
}
