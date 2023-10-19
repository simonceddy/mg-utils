<?php
namespace Eddy\Crawlers\PluginBoutique;

use Eddy\Robots\Robots;

class URL
{
    public const BASE = 'https://www.pluginboutique.com';

    public static function robots()
    {
        return self::BASE . '/' . Robots::TXT;
    }
}
