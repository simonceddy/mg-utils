<?php
namespace Eddy\Crawlers\Shared\FS;

use League\Flysystem\Filesystem;

class LoadRobots
{
    public function __construct(private Filesystem $fs)
    {}

    public function load(string $url)
    {
        $fn = Helper::urlToCacheFilename($url);
        dd($fn);
        $f = $this->fs->read($fn);
        dd($f);
    }
}
