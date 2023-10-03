<?php
namespace Eddy\Crawlers\PedalEmpire\Crawler;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class GetLastPage
{
    private static string $filter = 'body > #site-main > #shopify-section-static-collection > .productgrid--outer > .productgrid--wrapper > .pagination--container > ul';

    public function __construct(private DomCrawler $crawler)
    {}

    public function scan(?string $html = null)
    {
        if ($html) {
            $this->crawler->clear();
            $this->crawler->addContent($html);
        }

        try {
            $ul = $this->crawler->filter(static::$filter)->first();
            $totalLi = count($ul->children());
            $lastPage = $ul->children()->eq($totalLi - 2)->text();
    
            return $lastPage;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
