<?php
namespace Eddy\Crawlers\PedalEmpire;

use Eddy\Crawlers\PedalEmpire\Crawler\ScanCategory;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
    public function __construct(private DomCrawler $crawler)
    {}

    public function scanCategory(?string $html = null)
    {
        return (new ScanCategory($this->crawler))->scan($html);
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
