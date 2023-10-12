<?php
namespace Eddy\Crawlers\Shared;

class Robots
{
    public const TXT = 'robots.txt';

    private array $agents = [];
    
    private array $sitemaps = [];

    private ?string $sitemap = null;

    public function __construct(string $text)
    {
        $this->initData($text);
    }
    
    private function initData(string $text)
    {
        $bits = [...array_filter(explode("\n", $text), function($bit) {
            if (preg_match('/^\#\s?/', $bit)) return false;
            return strlen(trim($bit)) > 0;
        })];
        $agents = array_filter($bits, fn($bit) => str_starts_with($bit, 'User-agent'));
        $startId = null;
        $endId = null;
        $lastAgent = null;
        foreach ($agents as $id => $str) {
            if (isset($startId)) {
                $endId = $id;
                $params = array_slice($bits, $startId + 1, $endId - $startId - 1);
                $transformed = $this->prepareParams($params);
                $this->agents[$lastAgent] = $transformed;
            } 
            $startId = $id;
            $agent = preg_replace('/^User\-agent\:\s?/', '', $str);
    
            $lastAgent = $agent;
            // dd($agent);
        }
        // dd($this->agents);
        if (count($this->sitemaps) === 1) {
            $this->sitemap = array_key_first($this->sitemaps);
        }
    }

    private function prepareParams(array $params)
    {
        $transformed = [];
        foreach ($params as $param) {
            [$key, $val] = $this->prepareParam($param);
            if ($key === 'Sitemap') {
                $this->setSitemap($val);
            } else if (isset($transformed[$key])) {
                if (!is_array($transformed[$key])) {
                    $transformed[$key] = [
                        $transformed[$key]
                    ];
                }
                array_push($transformed[$key], $val);
            } else {
                $transformed[$key] = $val;
            }
        }
        return $transformed;
    }

    private function setSitemap(string $path)
    {
        $this->sitemaps[$path] = $path;
    }

    private function prepareParam(string $param)
    {
        $bits = array_map(fn($bit) => trim($bit), explode(':', $param, 2));
        return $bits;
    }

    public function sitemap()
    {
        return $this->sitemap ?? $this->sitemaps;
    }

    public function userAgents()
    {
        return array_keys($this->agents);
    }

    public function userAgent(string $agent = '*')
    {
        return $this->agents[$agent] ?? null;
    }

    public function disallow(string $agent = '*')
    {
        if (!isset($this->agents[$agent]) || !isset($this->agents[$agent]['Disallow'])) {
            return null;
        }
        return $this->agents[$agent]['Disallow'];
    }

    public function allow(string $agent = '*')
    {
        if (!isset($this->agents[$agent]) || !isset($this->agents[$agent]['Allow'])) {
            return null;
        }
        return $this->agents[$agent]['Allow'];
    }

    public function host(string $agent = '*')
    {
        if (!isset($this->agents[$agent]) || !isset($this->agents[$agent]['Host'])) {
            return null;
        }
        return $this->agents[$agent]['Host'];
    }

    public function crawlDelay(string $agent = '*')
    {
        if (!isset($this->agents[$agent]) || !isset($this->agents[$agent]['Crawl-Delay'])) {
            return null;
        }
        return $this->agents[$agent]['Crawl-Delay'];
    }
}
