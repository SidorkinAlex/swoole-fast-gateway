<?php

namespace Sidalex\Gateway\Classes\CoreLogicExecutors;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Sidalex\Gateway\Classes\Cache\CacheRequestCollector;


class DTOLogicExecutor
{
    private \stdClass $config;
    private Request $psr7Request;
    private \Swoole\Http\Request $swooleRequest;
    private CacheRequestCollector $cacheRequestCollector;
    /**
     * @var ResponseInterface|null
     */
    private mixed $response;

    /**
     * @param CacheRequestCollector $cacheRequestCollector
     * @param \stdClass $config
     * @param Request $psr7Request
     * @param \Swoole\Http\Request $swooleRequest
     * @param ResponseInterface|null $response
     */
    public function __construct(
        CacheRequestCollector $cacheRequestCollector,
        \stdClass             $config,
        Request               $psr7Request,
        \Swoole\Http\Request  $swooleRequest,
        ResponseInterface $response = null  // todo возможно упадет
    )
    {
        $this->config = $config;
        $this->psr7Request = $psr7Request;
        $this->swooleRequest = $swooleRequest;
        $this->cacheRequestCollector = $cacheRequestCollector;
        $this->response= $response;
    }

    /**
     * @return \stdClass
     */
    public function getConfig(): \stdClass
    {
        return $this->config;
    }

    /**
     * @return Request
     */
    public function getPsr7Request(): Request
    {
        return $this->psr7Request;
    }

    /**
     * @return \Swoole\Http\Request
     */
    public function getSwooleRequest(): \Swoole\Http\Request
    {
        return $this->swooleRequest;
    }

    /**
     * @return CacheRequestCollector
     */
    public function getCacheRequestCollector(): CacheRequestCollector
    {
        return $this->cacheRequestCollector;
    }

    /**
     * @return mixed
     */
    public function getResponse(): mixed
    {
        return $this->response;
    }

    public function hasResponse(): bool
    {
        if (($this->response instanceof ResponseInterface)){
            return true;
        }
        return false;
    }




}