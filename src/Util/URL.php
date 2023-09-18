<?php
namespace Eddy\ModularGrid\Util;

/**
 * URL Object
 * 
 * @method string forEurorack(string $slug, bool $noBase = false)
 * @method string forPedal(string $slug, bool $noBase = false)
 * @method string forBuchla(string $slug, bool $noBase = false)
 * @method string forFrac(string $slug, bool $noBase = false)
 * @method string forMOTM(string $slug, bool $noBase = false)
 * @method string forModcan(string $slug, bool $noBase = false)
 * @method string forMU(string $slug, bool $noBase = false)
 * @method string forSerge(string $slug, bool $noBase = false)
 * @method string for500(string $slug, bool $noBase = false)
 * @method string forAE(string $slug, bool $noBase = false)
 * @method static string forEurorack(string $slug, bool $noBase = false)
 * @method static string forPedal(string $slug, bool $noBase = false)
 * @method static string forBuchla(string $slug, bool $noBase = false)
 * @method static string forFrac(string $slug, bool $noBase = false)
 * @method static string forMOTM(string $slug, bool $noBase = false)
 * @method static string forModcan(string $slug, bool $noBase = false)
 * @method static string forMU(string $slug, bool $noBase = false)
 * @method static string forSerge(string $slug, bool $noBase = false)
 * @method static string for500(string $slug, bool $noBase = false)
 * @method static string forAE(string $slug, bool $noBase = false)
 * 
 */
class URL
{
    public const BASE = 'https://www.modulargrid.net';

    public static array $shorthand = [
        'eurorack' => 'e',
        'pedal' => 'p',
        'buchla' => 'u',
        'frac' => 'f',
        'modcan' => 'c',
        'motm' => 'm',
        'mu' => 'd',
        'serge' => 's',
        '500' => 'a',
        'ae' => 't',
    ];

    private function urlForCategory(string $category, bool $noBase = false)
    {
        if (in_array($category, static::$shorthand)) {
            $short = $category;
        } else if (isset(static::$shorthand[$category])) {
            $short = static::$shorthand[$category];
        } else {
            throw new \LogicException(
                'Unknown category: ' . $category
            );
        }

        return $noBase ? $short : static::BASE . '/' . $short;
    }

    private function urlForItem(string $category, $slug, bool $noBase = false)
    {
        return $this->urlForCategory($category, $noBase) . '/' . $slug;
    }

    public function __call($name, $arguments)
    {
        if (preg_match('/^for/', $name)
            && isset($arguments[0])
            && is_string($arguments[0])
        ) {
            $n = strtolower(preg_replace('/^for/', '', $name));
            return $this->urlForItem($n, ...$arguments);
        }
    }

    public static function __callStatic($name, $arguments)
    {
        if (preg_match('/^for/', $name)
            && isset($arguments[0])
            && is_string($arguments[0])
        ) {
            return call_user_func([new self(), $name], $arguments[0]);
        }
    }
}
