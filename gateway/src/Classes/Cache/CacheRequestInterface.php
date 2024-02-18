<?php

namespace Sidalex\Gateway\Classes\Cache;

interface CacheRequestInterface
{
    public function setCache(string $key, $value,int $ttl): void;

    public function getCache(string $key);

    public function hasKey(string $key): bool;
}