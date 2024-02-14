<?php

namespace Sidalex\Gateway\Classes\Cache;

class CacheRequestCollector
{
    protected MemoryCacheRequest $memoryCache;

    /**
     * @param MemoryCacheRequest $memoryCache
     */
    public function __construct(\stdClass $config)
    {
        $this->memoryCache = new MemoryCacheRequest();
    }

    /**
     * @return MemoryCacheRequest
     */
    public function getMemoryCache(): MemoryCacheRequest
    {
        return $this->memoryCache;
    }

}