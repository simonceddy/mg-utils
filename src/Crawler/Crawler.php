<?php
namespace Eddy\ModularGrid\Crawler;

use Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;

class Crawler
{
    private SymfonyCrawler $crawler;

    public function __construct(private string $document)
    {
        $this->crawler = new SymfonyCrawler($document);
    }

    public function itemLink()
    {
        return (new GetLink($this->crawler))->scan();
    }

    public function itemTags()
    {
        return (new ScanTags($this->crawler))->scan();
    }

    public function rackJson()
    {
        return (new GetRackJson($this->crawler))->scan();
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->crawler, $name)) {
            return call_user_func_array([$this->crawler, $name], $arguments);
        }
    }
}
