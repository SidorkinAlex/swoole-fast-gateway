<?php

namespace Sidalex\Gateway\Classes\Cache;

class MemoryCacheRequest implements CacheRequestInterface
{
    private array $repository = [];

    public function setCache(string $key, $value,int $ttl = 600): void
    {
        if ($ttl==0){
            $ttl = 9999999;
        }
        $this->repository[$key]['value'] = $value;
        $this->repository[$key]['time'] = (time()) + $ttl;

    }

    public function getCache(string $key)
    {
        return $this->repository[$key]['value'];
    }

    public function hasKey(string $key): bool
    {

        if (array_key_exists($key, $this->repository)) {

            if ($this->repository[$key]['time'] > time()) {
                return true;
            }
        }
        return false;
    }
}