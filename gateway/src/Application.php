<?php

namespace Sidalex\Gateway;

use HaydenPierce\ClassFinder\ClassFinder;
use Sidalex\Gateway\Classes\Cache\CacheRequestCollector;
use Sidalex\Gateway\Classes\Cache\ResponseCache;
use Sidalex\Gateway\Classes\Client\Requester;
use Sidalex\Gateway\Classes\Collectors\SwooleRequestMutationCollector;
use Sidalex\Gateway\Classes\Config\ConfigException;
use Sidalex\Gateway\Classes\Config\ConfigValidator;
use Sidalex\Gateway\Classes\CoreLogicExecutors\LogicExecutorInterface;
use stdClass;

class Application
{
    private stdClass $config;

    private CacheRequestCollector $repositoryCache;


    public function __construct()
    {
        $this->initConfig();
        $this->repositoryCache = new CacheRequestCollector($this->config);
    }


}