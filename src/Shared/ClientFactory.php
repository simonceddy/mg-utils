<?php
namespace Eddy\Crawlers\Shared;

use Eddy\Crawlers\Mannys\MannysURL;
use Eddy\Crawlers\ModularGrid\Util\URL as MGURL;
use Eddy\Crawlers\PedalEmpire\PedalEmpireURL;
use GuzzleHttp\Client as Guzzle;

/**
 * @method static Guzzle modularGrid()
 * @method static Guzzle pedalEmpire()
 * @method static Guzzle mannys()
 * 
 * @property Guzzle $modularGrid
 * @property Guzzle $pedalEmpire
 * @property Guzzle $mannys
 */
class ClientFactory
{
    private static array $configurations = [
        'modularGrid' => [
            'base-uri' => MGURL::BASE,
        ],
        'pedalEmpire' => [
            'base-uri' => PedalEmpireURL::BASE,
        ],
        'mannys' => [
            'base-uri' => MannysURL::BASE
        ],
    ];

    public function make(mixed $config = null, array $factoryOptions = [])
    {
        if (is_string($config) && isset(static::$configurations[$config])) {
            $config = static::$configurations[$config];
        } else if ($config !==null && !is_array($config)) {
            throw new \InvalidArgumentException(
                'Custom configuration must be an array!'
            );
        }
        return new Guzzle($config);
    }

    public static function __callStatic($name, $arguments)
    {
        if (isset(self::$configurations[$name])) {
            if (!empty($arguments)) {
                $name = [...self::$configurations[$name],  ...$arguments];
            }
            return (new self())->make($name);
        }

        throw new \BadMethodCallException(
            'Unknown static method: ' . $name
        );
    }

    public function __get($name)
    {
        if (isset(self::$configurations[$name])) {
            return (new self())->make($name);
        }

        throw new \OutOfBoundsException(
            'Undefined property: ' . $name
        );
    }
}
