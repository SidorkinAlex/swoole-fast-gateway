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

    /**
     * @throws ClientExceptionInterface
     */
    public function execute(Application $app): ResponseInterface
    {

        $handler = new SwooleHandler();
        /** @phpstan-ignore-next-line */
        $stack = HandlerStack::create($handler);
        $client = new Client
        ([
            'handler' => $stack,
            'http_errors' => false,
            'allow_redirects' => false,
        ]);

        $request = (new PsrRequestBuilder())->buildRequest($this->swooleRequest, $app->getConfig());
        $request = $this->psrMutationHook($request, $app);
        $dtoLogicExecutor = new DTOLogicExecutor($this->cacheRequestCollector, $app->getConfig(), $request, $this->swooleRequest);

        $dtoLogicExecutor = $this->beforeRequestLogicHook($dtoLogicExecutor);
        if ($dtoLogicExecutor->hasResponse()) {
            return $dtoLogicExecutor->getResponse();
        }
    }

    private function psrMutationHook(Request $request, Application $app) :Request
    {
        $psrRequestMutationCollector = new PsrRequestMutationCollector($app->getConfig());
        if ($psrRequestMutationCollector->getCountMutationExecutors() > 0) {
            foreach ($psrRequestMutationCollector->getMutationExecutors() as $MutationExecutor) {
                $request = $MutationExecutor->mutation($request);
            }
        }
        return $request;
    }

    private function beforeRequestLogicHook(DTOLogicExecutor $dtoLogicExecutor): DTOLogicExecutor
    {
        $beforeSendingLogicExecutorCollector = new BeforeSendingLogicExecutorCollector($dtoLogicExecutor);
        if ($beforeSendingLogicExecutorCollector->getCountMutationExecutors() > 0) {
            $beforeSendingLogicExecutorCollector->executeCollection();
            $dtoLogicExecutor = $beforeSendingLogicExecutorCollector->getDTOLogicExecutor();
        }
        unset($beforeSendingLogicExecutorCollector);
        return $dtoLogicExecutor;

    }


}