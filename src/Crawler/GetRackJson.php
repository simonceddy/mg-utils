<?php
namespace Eddy\ModularGrid\Crawler;

use Symfony\Component\DomCrawler\Crawler;

class GetRackJson
{
    private static string $filter = 'body > #content-wrapper > script';

    public function __construct(private Crawler $crawler)
    {}

    public function scan(?string $html = null)
    {
        if ($html) {
            $this->crawler->clear();
            $this->crawler->addContent($html);
        }

        try {
            $json = $this->crawler
                ->filter(static::$filter)
                ->first();
            if ($json->count() > 0) {
                return $json->text();
            }
                
            // dd($url);
    
            return null;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
