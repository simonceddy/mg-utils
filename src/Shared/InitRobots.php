<?php
namespace Eddy\Crawlers\Shared;

use Eddy\Robots\{Factory, Robots};
use GuzzleHttp\Client as Guzzle;
use League\Flysystem\Filesystem;

class InitRobots
{
    public function __construct(private Guzzle $client, private ?Filesystem $fs = null)
    {
        if (!$fs) $this->fs = FS\Factory::make();
    }

    public function from(string $url): ?Robots
    {
        $res = $this->client->request('GET', $url, [
            'timeout' => 5,
            'http_errors' => false
        ]);
        if ($res->getStatusCode() !== 200) {
            $body = (new FS\LoadRobots($this->fs))->load($url);
            if (!$body) return null;
        } else {
            $body = $res->getBody()->getContents();
            (new FS\StoreRobots($this->fs))->save($url, $body);
        }

        return Factory::make($body);
    }
}
