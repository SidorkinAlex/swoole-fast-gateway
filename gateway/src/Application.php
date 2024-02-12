<?php

namespace Sidalex\Gateway;

use HaydenPierce\ClassFinder\ClassFinder;
use Sidalex\Gateway\Classes\Cache\CacheRequestCollector;
use Sidalex\Gateway\Classes\Client\Requester;
use Sidalex\Gateway\Classes\Collectors\SwooleRequestMutationCollector;
use Sidalex\Gateway\Classes\CoreLogicExecutors\LogicExecutorInterface;
use stdClass;

class Application
{
    private \Swoole\Tables $tables;
    private stdClass $config;

    private CacheRequestCollector $repositoryCache;


    public function __construct()
    {
        $this->config = json_decode(file_get_contents(__DIR__ . "/../config.json"));
        $this->add2Config();
        $this->repositoryCache = new CacheRequestCollector($this->config);
    }


}