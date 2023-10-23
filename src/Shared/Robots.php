<?php
namespace Eddy\Crawlers\Shared;

use Eddy\Robots\Factory;
use Eddy\Robots\Robots as RobotsRobots;

class Robots
{
    public const TXT = 'robots.txt';

    private RobotsRobots $robots;

    private static string $guzzleUserAgent = 'GuzzleHttp/7';

    public function __construct(mixed $text)
    {
        if (is_string($text)) $this->initData($text);
        else if (!($text instanceof RobotsRobots)) {
            throw new \InvalidArgumentException(
                'Invalid robots!'
            );
        } else $this->robots = $text;
    }
    
    private function initData(string $text)
    {
        $this->robots = Factory::make($text);
    }

    public function sitemap(?string $agent = '*')
    {
        $a = $this->robots->getAgent($agent);
        if ($a) return $a->getSitemap();
        return null;
    }

    public function userAgents()
    {
        return $this->robots->getAgents();
    }

    public function userAgent(string $agent = '*')
    {
        return $this->robots->getAgent[$agent] ?? null;
    }

    public function disallow(string $agent = '*')
    {
        $a = $this->robots->getAgent($agent);
        if ($a) return $a->getDisallowed();
        return null;
    }

    public function allow(string $agent = '*')
    {
        $a = $this->robots->getAgent($agent);
        if ($a) return $a->getAllowed();
        return null;
    }

    public function host(string $agent = '*')
    {
        $a = $this->robots->getAgent($agent);
        if ($a) return $a->getHosts();
        return null;
    }

    public function crawlDelay(string $agent = '*')
    {
        $a = $this->robots->getAgent($agent);
        if ($a) return $a->getCrawlDelay();
        return null;
    }

    public static function clientAgent()
    {
        return self::$guzzleUserAgent;
    }

    public static function from(string $url)
    {
        if (!str_ends_with($url, '/')) $url .= '/';
        return $url . self::TXT;
    }
}
