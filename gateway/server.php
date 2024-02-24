#!/usr/bin/env php
<?php

declare(strict_types=1);

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use Swoole\Coroutine;


require_once "./vendor/autoload.php";
$http = new Server("0.0.0.0", 9501);
$app = new \Sidalex\Gateway\Application();
$http->on(
    "start",
    function (Server $http) use ($app) {
        echo "Swoole HTTP server is started.\n";
        go(function () use ($app) {
            while (true) {
                Coroutine::sleep(50);
                $app->getRepositoryCache()
                    ->getMemoryCache()
                    ->validateCache();
            }
        });
    }
);
$http->start();
