<?php
namespace Eddy\Crawlers\Shared\FS;

use League\Flysystem\Filesystem;

class StoreRobots
{
    public function __construct(private Filesystem $fs)
    {}

    public function save(string $url, string $data)
    {
        $fn = Helper::urlToCacheFilename($url);
        // dd($fn);
        $this->fs->write($fn, $data);
    }
}
