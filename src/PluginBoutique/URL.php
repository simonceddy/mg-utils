<?php
namespace Eddy\Crawlers\PluginBoutique;

use Eddy\Robots\Robots;

/**
 * @property string $product
 * @property string $robots
 * @method string product(?string $product = null)
 * @method string robots()
 */
class URL
{
    public const BASE = 'https://www.pluginboutique.com';

    public function __call($name, $arguments)
    {
        if ($name === 'product') return static::product(...$arguments);
        if ($name === 'robots') return static::robots();

        throw new \BadMethodCallException(
            'Undefined method: ' . $name
        );
    }

    public function __get($name)
    {
        switch ($name) {
            case 'product':
                return static::product();
            case 'robots':
                return static::robots();
        }

        throw new \OutOfBoundsException(
            'Undefined property: ' . $name
        );
    }

    public static function product(string $product = ''): string
    {
        $product = basename($product);
        // dd($product);
        return self::BASE . '/products/' . $product;
    }

    public static function robots(): string
    {
        return self::BASE . '/' . Robots::TXT;
    }
}
