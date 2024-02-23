<?php

namespace Sidalex\Gateway\Classes\Client;


use co;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Sidalex\Gateway\Application;
use Sidalex\Gateway\Classes\Cache\CacheRequestCollector;
use Sidalex\Gateway\Classes\Collectors\AfterSendingLogicExecutorCollector;
use Sidalex\Gateway\Classes\Collectors\PsrRequestMutationCollector;
use Sidalex\Gateway\Classes\Collectors\BeforeSendingLogicExecutorCollector;
use Sidalex\Gateway\Classes\CoreLogicExecutors\DTOLogicExecutor;
use Sidalex\Gateway\Classes\PsrRequestBuilder\PsrRequestBuilder;
use Yurun\Util\Swoole\Guzzle\SwooleHandler;

class Requester
{


    private \Swoole\Http\Request $swooleRequest;
    private CacheRequestCollector $cacheRequestCollector;

    public function __construct(\Swoole\Http\Request $request, CacheRequestCollector $cacheRequestCollector)
    {
        $this->swooleRequest = $request;
        $this->cacheRequestCollector = $cacheRequestCollector;
    }


}