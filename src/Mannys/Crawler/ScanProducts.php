<?php
namespace Eddy\Crawlers\Mannys\Crawler;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class ScanProducts
{
    private static string $filter = 'body > #formProductDisplay > #cv-zone-layout > #cv-zone-container-1 > #cv-zone-container-2 > #cv-zone-pagecontent > section > #productdisplaywrapper > #product_display_container > #cv-zone-scl-14-full-middle-bottom > .container > #cv-zone-scl-14-middle-bottom-right > #cv-zone-scl-14-middle-bottom-right-bottom > #product-grid';

    public function __construct(private DomCrawler $crawler)
    {}

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
            $getName = new GetProductName($this->crawler);
            foreach ($p as $product) {
               dump($getName->attemptNameExtraction($product));
            }
            // dd($url);
    
            return null;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
