<?php
namespace Eddy\Crawlers\Mannys;

/**
 * @method string forBassPedals()
 * @method string forDistortionPedals()
 */
class MannysURL
{
    private static array $shortcuts = [
        'bassPedals' => 'guitar-bass/pedals-and-effects/bass-guitar-pedals',
        'distortionPedals' => 'guitar-bass/pedals-and-effects/distortion-and-overdrive',
    ];

    private static array $categories = [
        'guitar-bass' => 'guitar-bass',
        'keyboards' => 'keyboards',
        'drums' => 'drums',
        'live-sound-pa' => 'live-sound-pa',
        'lights-lasers' => 'lights-lasers',
        'dj-equipment' => 'dj-equipment',
        'studio-gear' => 'studio-gear',
        'headphones' => 'headphones',
        'accessories' => 'accessories',
    ];

    public const BASE = 'https://www.mannys.com.au/';

    public function __construct()
    {}

    public function make(string $path)
    {
        if (!str_starts_with($path, '/')) $path = '/' . $path;
        return static::BASE . $path;
    }

    public function forCategory(string $category)
    {
        if (isset(static::$categories[$category])) {
            return $this->make(static::$categories[$category]);
        }
        return false;
    }

    public function __call($name, $arguments)
    {
        if (str_starts_with($name, 'for')) {
            $name = lcfirst(substr($name, 3));
            if (isset(static::$shortcuts[$name])) {
                return $this->make(static::$shortcuts[$name]);
            }
        }

        throw new \BadMethodCallException('Undefined method: ' . $name);
    }
}
