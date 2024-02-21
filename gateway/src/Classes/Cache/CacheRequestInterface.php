<?php

namespace Sidalex\Gateway\Classes\Cache;

interface CacheRequestInterface
{
    public function setCache(string $key, mixed $value, int $ttl): void;

    public function getCache(string $key): mixed;

    public function hasKey(string $key): bool;
}