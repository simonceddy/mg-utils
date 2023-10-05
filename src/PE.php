<?php
namespace Eddy\Crawlers;

use Eddy\Crawlers\PedalEmpire\Crawler;
use Eddy\Crawlers\PedalEmpire\PedalEmpireURL;
use Eddy\Crawlers\Shared\ClientFactory;
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

    public function __construct(
        private ?Guzzle $client = null,
        private ?SymCrawler $symCrawler = null,
    ) {
        $this->peUrl = new PedalEmpireURL();
        if (!$client) $this->client = ClientFactory::pedalEmpire();
        if (!$symCrawler) $this->symCrawler = new SymCrawler();
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
