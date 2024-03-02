<?php

namespace Sidalex\Gateway;

use GuzzleHttp\Psr7\Response;
use HaydenPierce\ClassFinder\ClassFinder;
use Psr\Http\Client\ClientExceptionInterface;
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

    private CacheRequestCollector $cacheRequestCollector;


    public function __construct()
    {
        $this->initConfig();
        $this->cacheRequestCollector = new CacheRequestCollector($this->config);
    }

    private function initConfig(): void
    {
        $content = file_get_contents(__DIR__ . "/../config.json");
        if ($content === false) {
            echo "config.json not exist or is not readable";
            exit(1);
        }
        $config_body = json_decode($content);
        unset($content);
        if ($config_body === false) {
            echo "config.json is not set or is not valid JSON";
            exit(1);
        }
        $this->config = $config_body;
        unset($config_body);
        try {
            $this->config = ConfigValidator::Validate($this->config);
        } catch (ConfigException $e) {
            echo $e->getMessage() . " error code is" . $e->getCode();
            exit(1);
        }
        $this->add2Config();
    }

    private function add2Config(): void
    {
        $namespace = [
            'AfterSendingLogicExecutors' => 'Sidalex\Gateway\Classes\AfterSendingLogicExecutors',
            'BeforeSendingLogicExecutors' => 'Sidalex\Gateway\Classes\BeforeSendingLogicExecutors',
        ];
        try {
            $classes = ClassFinder::getClassesInNamespace($namespace['AfterSendingLogicExecutors']);
            foreach ($classes as $class) {
                $object = new $class();
                if ($object instanceof LogicExecutorInterface) {
                    $this->config->AfterSendingLogicExecutors[] = $class;
                }
                unset($object);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit(1);
        }

        try {
            $classes = ClassFinder::getClassesInNamespace($namespace['BeforeSendingLogicExecutors']);
            foreach ($classes as $class) {
                $object = new $class();
                if ($object instanceof LogicExecutorInterface) {
                    $this->config->BeforeSendingLogicExecutors[] = $class;
                }
                unset($object);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit(1);
        }
    }

    public function getRepositoryCacheCollector(): CacheRequestCollector
    {
        return $this->cacheRequestCollector;
    }

    public function proxy(\Swoole\Http\Request $request, \Swoole\Http\Response $response): void
    {

        $request = $this->swooleRequestMutationHook($request);

        $client = new Requester($request, $this->getRepositoryCacheCollector());

        try {
            $response_data = $client->execute($this);

        } catch (ClientExceptionInterface $e) {
            $body = json_encode(
                [
                    "errorMessage" => $e->getMessage(),
                ]
            );
            if ($body === false) {
                $body = "internal error";
            }
            $response_data = new Response(500, ["Content-Type" => 'application/json'],
                $body
            );
        }
        $this->swooleBuildResponse($response_data, $response);

    }

    private function swooleRequestMutationHook(\Swoole\Http\Request $request): \Swoole\Http\Request
    {
        $swooleRequestMutationCollector = new SwooleRequestMutationCollector($this->config);
        if ($swooleRequestMutationCollector->getCountMutationExecutors() > 0) {
            foreach ($swooleRequestMutationCollector->getMutationExecutors() as $MutationExecutor) {
                $request = $MutationExecutor->mutation($request);
            }
        }
        return $request;
    }

    private function swooleBuildResponse(\Psr\Http\Message\ResponseInterface $response_data, \Swoole\Http\Response &$response): void
    {
        foreach ($response_data->getHeaders() as $headerName => $headerValue) {
            if ($headerName == 'content-length') {
                continue;
            }
            if ($headerName == 'content-encoding') {
                continue;
            }
            $response->setHeader($headerName, $headerValue);
        }
        $response->setStatusCode($response_data->getStatusCode());
        $response->end($response_data->getBody());
    }

    /**
     * @return stdClass
     */
    public function getConfig(): stdClass
    {
        return $this->config;
    }


}