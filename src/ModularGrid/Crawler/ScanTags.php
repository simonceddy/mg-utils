<?php
namespace Eddy\Crawlers\ModularGrid\Crawler;

use Symfony\Component\DomCrawler\Crawler;

class ScanTags
{
    public function __construct(private Crawler $crawler)
    {}

    public function scan(?string $html = null)
    {
        if ($html) {
            $this->crawler->clear();
            $this->crawler->addContent($html);
        }

        try {
            $tags = $this->crawler
                ->filter('body > #content-wrapper > .modules-view > .grid-container > .g-headr')
                ->children()
                ->first()
                ->filter('.module-tags')
                ->first()->children();
            $tagsText = [];
            foreach ($tags as $tag) {
                // dd($tag->attributes['href']);
                array_push($tagsText, $tag->textContent);
            }
            // dd($tagsText);
    
            return $tagsText;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
