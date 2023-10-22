<?php
namespace Eddy\Crawlers\Shared;

use Symfony\Component\DomCrawler\Crawler;

class CrawlerFactory
{
    public static function make(mixed $node = null)
    {
        return new Crawler($node, null, null, true);
    }
}
