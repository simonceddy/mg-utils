<?php
namespace Eddy\Crawlers\Shared\Cache;

use Kevinrob\GuzzleCache\CacheEntry;
use Kevinrob\GuzzleCache\Storage\CacheStorageInterface;
use League\Flysystem\Filesystem;

class FSCache implements CacheStorageInterface
{
    public function __construct(private Filesystem $filesystem)
    {}

    public function fetch($key)
    {
        if ($this->filesystem->has($key)) {
            $data = @unserialize(
                $this->filesystem->read($key)
            );

            if ($data instanceof CacheEntry) {
                return $data;
            }
        }

        return;
    }

    public function save($key, CacheEntry $data)
    {
        return $this->filesystem->write($key, serialize($data));
    }

    public function delete($key)
    {
        try {
            return $this->filesystem->delete($key);
        } catch (\Throwable $ex) {
            return true;
        }
    }
}
