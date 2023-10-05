<?php
namespace Eddy\Crawlers\PedalEmpire\Crawler;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class GetProductJSON
{
    private static string $filter = 'body > #site-main > #shopify-section-static-product > script';

    public function __construct(private DomCrawler $crawler)
    {}

    public function scan(?string $html = null)
    {
        if ($html) {
            $this->crawler->clear();
            $this->crawler->addContent($html);
        }

        try {
            $script = $this->crawler->filter(static::$filter);
            if (count($script) === 0) return null;
            $sEl = $script->first();
            $data = json_decode($sEl->text(), true);
            return $data;
            
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
