<?php
namespace Eddy\ModularGrid\Util;

/**
 * URL Object
 * 
 * @method string forEurorack(string $slug)
 * @method string forPedal(string $slug)
 * @method string forBuchla(string $slug)
 * @method string forFrac(string $slug)
 * @method string forMOTM(string $slug)
 * @method string forModcan(string $slug)
 * @method string forMU(string $slug)
 * @method string forSerge(string $slug)
 * @method string for500(string $slug)
 * @method string forAE(string $slug)
 * @method static string forEurorack(string $slug)
 * @method static string forPedal(string $slug)
 * @method static string forBuchla(string $slug)
 * @method static string forFrac(string $slug)
 * @method static string forMOTM(string $slug)
 * @method static string forModcan(string $slug)
 * @method static string forMU(string $slug)
 * @method static string forSerge(string $slug)
 * @method static string for500(string $slug)
 * @method static string forAE(string $slug)
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

    private function urlForCategory(string $category)
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

        return static::BASE . '/' . $short;
    }

    private function urlForItem(string $category, $slug)
    {
        return $this->urlForCategory($category) . '/' . $slug;
    }

    public function __call($name, $arguments)
    {
        if (preg_match('/^for/', $name)
            && isset($arguments[0])
            && is_string($arguments[0])
        ) {
            $n = strtolower(preg_replace('/^for/', '', $name));
            return $this->urlForItem($n, $arguments[0]);
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
