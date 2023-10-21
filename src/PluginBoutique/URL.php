<?php
namespace Eddy\Crawlers\PluginBoutique;

use Eddy\Robots\Robots;

/**
 * @property string $product
 * @property string $robots
 */
class URL
{
    public const BASE = 'https://www.pluginboutique.com';

    public function __get($name)
    {
        switch ($name) {
            case 'product':
                return static::product();
            case 'robots':
                return static::robots();
        }
    }

    public static function product(string $product = ''): string
    {
        return self::BASE . '/products/' . $product;
    }

    public static function robots(): string
    {
        return self::BASE . '/' . Robots::TXT;
    }
}
