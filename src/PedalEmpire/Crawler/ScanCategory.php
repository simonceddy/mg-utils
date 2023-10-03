<?php
namespace Eddy\Crawlers\PedalEmpire\Crawler;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class ScanCategory
{
    private static $startPosFilter1 = ',"productVariants":[{';
    private static $endPosFilter1 = '}});},"https:';
    
    private static $startPosFilter2 = 'var meta = {"products":';
    private static $endPosFilter2 = ',"page":{"pageType":"collection","resourceType":"collection",';
        
    private static string $filter = '#web-pixels-manager-setup';

    public function __construct(private DomCrawler $crawler)
    {}

    private function getDataFromMetaVar(string $js)
    {
        $l = strlen($js);
        $start = strpos($js, static::$startPosFilter2) + strlen(static::$startPosFilter2);
        $end = strpos($js, static::$endPosFilter2);
        $js = substr($js, $start, $end - $start);
        // dd($js);
        $data = json_decode($js, true);
        return $data;
    }

    private function getDataFromManagerSetup(string $js)
    {
        $l = strlen($js);
        $start = (strpos($js, static::$startPosFilter1) + strlen(static::$startPosFilter1)) - 2;
        $end = (strpos($js, static::$endPosFilter1)/*  - strlen(static::$endPosFilter1) */);
        // dd($end - $start);
        $js = substr($js, $start, $end - $start);
        $data = json_decode($js, true);
        return $data;
    }

    public function scan(?string $html = null)
    {
        if ($html) {
            $this->crawler->clear();
            $this->crawler->addContent($html);
        }

        try {
            $head = $this->crawler->filter('head')->first();
            $elCount = count($head->children());
            $p = $head
                ->filter(static::$filter)
                ->first();
            $js1 = $p->text();
            $data1 = $this->getDataFromManagerSetup($js1);

            $js2 = ($head->children()->eq($elCount - 4)->text());
            $data2 = $this->getDataFromMetaVar($js2);
            
            $data = [];

            foreach ($data1 as $key => $bit1) {
                if ($data2[$key]) {
                    $data[$key] = [
                        ...$data2[$key],
                        ...$bit1,
                    ];
                } else $data[$key] = $bit1;
            }
            // dd($data);

            return $data;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
