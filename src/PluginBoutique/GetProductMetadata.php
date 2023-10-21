<?php
namespace Eddy\Crawlers\PluginBoutique;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class GetProductMetadata
{
    private static string $filter = 'body > .js-site > .banner_background > .content > .page-product';

    public function __construct(private DomCrawler $crawler)
    {}

    private function getData(\DOMNode $node)
    {
        dd($node);
        foreach ($node->childNodes as $childNode) {
            dump($childNode);
        }
    }

    private function scanChildNodes(DomCrawler $nodes)
    {
        foreach ($nodes as $node) {
            $data = $this->getData($node);
        }
    }

    public function scan(?string $html = null)
    {
        if ($html) {
            $this->crawler->clear();
            $this->crawler->addContent($html);
        }

        $el = $this->crawler->filter(static::$filter)
            ->first();
        if ($el && $el->children()->count() > 0) {
            $this->scanChildNodes($el->children());
        }
    }
}
