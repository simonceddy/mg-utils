<?php
namespace Eddy\Crawlers\ModularGrid\Crawler;

use Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;

/**
 * Crawler convenience wrapper
 * 
 * @method ?string itemLink()
 * @method ?string itemTags()
 * @method ?string imageURLs()
 * @method ?string rackJson()
 */
class Crawler
{
    private SymfonyCrawler $crawler;

    private static array $crawlers = [
        'itemLink' => GetLink::class,
        'imageURLs' => GetImageURLs::class,
        'itemTags' => ScanTags::class,
        'rackJson' => GetRackJson::class,
    ];

    public function __construct(private string $document)
    {
        $this->crawler = new SymfonyCrawler($document);
    }

    public function __call($name, $arguments)
    {
        if (isset(static::$crawlers[$name])) {
            return (new static::$crawlers[$name]($this->crawler))->scan();
        }
        if (method_exists($this->crawler, $name)) {
            return call_user_func_array([$this->crawler, $name], $arguments);
        }
    }
}
