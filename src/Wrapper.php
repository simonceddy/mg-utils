<?php
namespace Eddy\ModularGrid;

use Eddy\ModularGrid\Util\URL;
use GuzzleHttp\Client as Guzzle;

/**
 * Wrapper class for basic functionality
 * 
 * @property URL $url URL factory
 * @property Guzzle $guzzle
 * @property Guzzle $client
 */
class Wrapper
{
    public function __construct(private ?Guzzle $guzzle = null)
    {
        if (!$guzzle) $this->guzzle = new Guzzle();
    }

    public function __get($name)
    {
        if ($name === 'url') return new URL();
        if ($name === 'guzzle' || $name === 'client') return $this->guzzle;

        throw new \OutOfRangeException('Undefined property: ' . $name);
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->guzzle, $name)) {
            return call_user_func_array([$this->guzzle, $name], $arguments);
        }

        throw new \BadMethodCallException(
            'Undefined method: ' . $name
        );
    }
}
