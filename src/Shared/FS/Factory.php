<?php
namespace Eddy\Crawlers\Shared\FS;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

class Factory
{
    public static function make(): Filesystem
    {
        $cacheDir = self::root_dir() . DIRECTORY_SEPARATOR . 'cache';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir);
        }
        $adapter = new LocalFilesystemAdapter($cacheDir);
        return new Filesystem($adapter);
    }

    public static function root_dir(): string
    {
        return dirname(dirname(dirname(__DIR__)));
    }
}
