<?php

namespace Sidalex\Gateway\Classes\Cache;

class CacheRequestCollector
{
    protected MemoryCacheRequest $memoryCache;

    public function __construct(\stdClass $config)
    {
        if (isset($config->cacheMemoryClasses)) {
            $this->memoryCache = new MemoryCacheRequest();
        }
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