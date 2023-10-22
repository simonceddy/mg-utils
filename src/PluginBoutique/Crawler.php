<?php
namespace Eddy\Crawlers\PluginBoutique;

use Eddy\Crawlers\Shared\CrawlerFactory;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
    private GetProductMetadata $productMetadataScanner;

    public function __construct(
        private ?DomCrawler $domCrawler = null
    ) {
        if (!isset($domCrawler)) $this->domCrawler = CrawlerFactory::make();
    }

    public function getProductMetadata(?string $html = null)
    {
        if (!isset($this->productMetadataScanner)) {
            $this->productMetadataScanner = new GetProductMetadata($this->domCrawler);
        }
        return $this->productMetadataScanner->scan($html);
    }
}
