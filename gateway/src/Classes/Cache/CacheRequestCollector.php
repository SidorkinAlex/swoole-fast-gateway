<?php

namespace Sidalex\Gateway\Classes\Cache;

use Psr\SimpleCache\CacheInterface;

class CacheRequestCollector
{
    protected MemoryCacheRequest $memoryCache;
    /**
     * @var array<CacheInterface>
     */
    private array $cacheRepository;

    public function __construct(\stdClass $config)
    {
        if (
            isset($config->cacheClasses) &&
            is_array($config->cacheClasses) &&
            !empty($config->cacheClasses)
        ) {
            foreach ($config->cacheClasses as $key => $value) {
                //todo сделать правильную проверку при инициализации конфига
                try {
                    $reflectionClass = new \ReflectionClass($value->className);
                    $cacheClass = $reflectionClass->newInstanceArgs($value->constructorParameters);
                    if ($cacheClass instanceof CacheInterface){
                        $this->cacheRepository[$value->className] = $cacheClass;
                    } else {
                        echo "class ".$value->className."is not implemented Psr\SimpleCache\CacheInterface. this class not loaded";
                    }
                } catch (\Exception $e) {
                    echo "Error: " . $e->getMessage();
                    exit(1);
                }
            }

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