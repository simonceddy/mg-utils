<?php
namespace Eddy\Crawlers\Mannys\Crawler;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class GetProductName
{
    private static string $filter = 'body > #formProductDisplay > #cv-zone-layout > #cv-zone-container-1 > #cv-zone-container-2 > #cv-zone-pagecontent > section > #productdisplaywrapper > #product_display_container > #cv-zone-scl-14-full-middle-bottom > .container > #cv-zone-scl-14-middle-bottom-right > #cv-zone-scl-14-middle-bottom-right-bottom > #product-grid';

    public function __construct(private DomCrawler $crawler)
    {}

    public function attemptNameExtraction(\DOMNode $node)
    {
        $name = $node
            ->childNodes
            ->item(0)
            ->childNodes
            ->item(3)
            ->childNodes
            ->item(1)
            ->childNodes
            ->item(1)
            ->textContent;
        return $name;
    }

    public function scan(?string $html = null)
    {
        if ($html) {
            $this->crawler->clear();
            $this->crawler->addContent($html);
        }

        try {
            $p = $this->crawler
                ->filter(static::$filter)
                ->first()
                ->children()
                ->first()
                ->children();

            foreach ($p as $product) {
               dump($this->attemptNameExtraction($product));
            }
            // dd($url);
    
            return null;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
