<?php
namespace Eddy\Crawlers\Shared;

use Eddy\Crawlers\Mannys\MannysURL;
use Eddy\Crawlers\ModularGrid\Util\URL as MGURL;
use Eddy\Crawlers\PedalEmpire\PedalEmpireURL;
use Eddy\Crawlers\PluginBoutique\URL as PBURL;
use Eddy\Crawlers\Shared\Cache\FSCache;
use Eddy\Crawlers\Shared\FS\Factory as FSFactory;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Facades\Cache;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\LaravelCacheStorage;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;

/**
 * @method static Guzzle modularGrid()
 * @method static Guzzle pedalEmpire()
 * @method static Guzzle mannys()
 * @method static Guzzle pluginBoutique()
 * 
 * @property Guzzle $modularGrid
 * @property Guzzle $pedalEmpire
 * @property Guzzle $mannys
 * @property Guzzle $pluginBoutique
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
        'pluginBoutique' => [
            'base-uri' => PBURL::BASE
        ],
    ];

    private static function makeHandler()
    {
        $stack = HandlerStack::create();
        $stack->push(new CacheMiddleware(
            new PrivateCacheStrategy(
                new FSCache(FSFactory::make())
            )
        ), 'cache');

        return $stack;
    }

    public function make(mixed $config = null, array $factoryOptions = [])
    {
        if (is_string($config) && isset(static::$configurations[$config])) {
            $config = static::$configurations[$config];
        } else if ($config !==null && !is_array($config)) {
            throw new \InvalidArgumentException(
                'Custom configuration must be an array!'
            );
        }

        $handler = static::makeHandler();
        $conf = [
            ...(is_array($config) ? $config : $factoryOptions),
            'handler' => $handler
        ];
        return new Guzzle($conf);
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
