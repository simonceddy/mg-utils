<?php
namespace Eddy\Crawlers\Mannys;

use Eddy\Crawlers\Mannys\Crawler\GetProductName;
use Eddy\Crawlers\Mannys\Crawler\ScanProducts;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
    public function __construct(private DomCrawler $crawler)
    {}

    public function getName(?string $html = null)
    {
        return (new GetProductName($this->crawler))->scan($html);
    }

    public function scanProducts(?string $html = null)
    {
        return (new ScanProducts($this->crawler))->scan($html);
    }

    public function scan(?string $html = null)
    {
        try {
            return $this->scanProducts($html);
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
