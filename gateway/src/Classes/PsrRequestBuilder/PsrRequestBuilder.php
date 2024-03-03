<?php

namespace Sidalex\Gateway\Classes\PsrRequestBuilder;

use GuzzleHttp\Psr7\Request as PsrRequest;
use Swoole\Http\Request;

class PsrRequestBuilder
{
    public function buildRequest(Request $swooleRequest,\stdClass $config): PsrRequest
    {
        $host = (new HostBuilder)->hostBuild($config);
        $uri = $host.$swooleRequest->server['request_uri'];
        if (isset($swooleRequest->server['query_string']) && !empty($swooleRequest->server['query_string'])){
            $uri.="?".$swooleRequest->server['query_string'];
        }
        unset( $swooleRequest->header['host']);
        if (is_string($swooleRequest->getContent())){
            return new PsrRequest($swooleRequest->getMethod(),$uri ,$swooleRequest->header,$swooleRequest->getContent());
        }
        return new PsrRequest($swooleRequest->getMethod(),$uri ,$swooleRequest->header,'');
    }

}