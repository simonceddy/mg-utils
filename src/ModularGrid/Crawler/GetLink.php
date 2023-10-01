<?php
namespace Eddy\Crawlers\ModularGrid\Crawler;

use Symfony\Component\DomCrawler\Crawler;

class GetLink
{
    private static string $filter = 'body > #content-wrapper > .modules-view > .grid-container > .g-descr > #module-details';

    public function __construct(private Crawler $crawler)
    {}

    public function scan(?string $html = null)
    {
        if ($html) {
            $this->crawler->clear();
            $this->crawler->addContent($html);
        }

        try {
            $a = $this->crawler
                ->filter(static::$filter)
                ->children()
                ->last()
                ->filter('a');
            if ($a->count() > 0) {
                return $a->attr('href');
            }
                
            // dd($url);
    
            return null;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
