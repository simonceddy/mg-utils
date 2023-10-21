<?php
namespace Eddy\Crawlers\Mannys;

use Eddy\Crawlers\Shared\ClientFactory;
use GuzzleHttp\Client as Guzzle;

/**
 * @property MannysURL $url
 */
class Client
{
    public function __construct(private ?Guzzle $guzzle = null)
    {
        if (!$guzzle) {
            $this->guzzle = ClientFactory::mannys();
        }
    }

    public function __get($name)
    {
        if ($name === 'url') {
            return new MannysURL();
        }
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->guzzle, $name)) {
            return call_user_func_array([$this->guzzle, $name], $arguments);
        }

        throw new \BadMethodCallException('Unknown method: ' . $name);
    }
}
