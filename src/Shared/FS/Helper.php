<?php
namespace Eddy\Crawlers\Shared\FS;

final class Helper
{
    public static function urlToCacheFilename(string $url)
    {
        $fn = preg_replace([
            '/\./', '/\:\/\//', '/\//',
        ], '-', $url);
        return $fn;
    }
}
