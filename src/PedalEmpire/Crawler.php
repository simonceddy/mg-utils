<?php
namespace Eddy\Crawlers\PedalEmpire;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
    private Crawler\GetLastPage $getLastPageCrawler;

    private Crawler\ScanCategory $scanCategoryCrawler;

    public function __construct(private DomCrawler $crawler)
    {
        $this->getLastPageCrawler = new Crawler\GetLastPage($crawler);
        $this->scanCategoryCrawler = new Crawler\ScanCategory($crawler);
    }

    public function scanCategoryPage(?string $html = null)
    {
        return $this->scanCategoryCrawler->scan($html);
    }

    public function scanCategory(?string $html = null)
    {
        return $this->scanCategoryCrawler->scan($html);
    }

    public function getLastPage(?string $html = null)
    {
        return $this->getLastPageCrawler->scan($html);

    }

    public function scan(?string $html = null)
    {
        if ($html) {
            $this->crawler->clear();
            $this->crawler->addContent($html);
        }

        try {
            dd($this->scanCategory($html));
            return null;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
