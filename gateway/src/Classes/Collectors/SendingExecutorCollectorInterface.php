<?php

namespace Sidalex\Gateway\Classes\Collectors;

use GuzzleHttp\Psr7\Request;
use Sidalex\Gateway\Classes\Cache\CacheRequestCollector;
use Sidalex\Gateway\Classes\CoreLogicExecutors\DTOLogicExecutor;

interface SendingExecutorCollectorInterface
{
    public function __construct(
        DTOLogicExecutor $DTOLogicExecutor
    );

    public function getCountMutationExecutors(): int;


    public function executeCollection(): void;

    /**
     * @return DTOLogicExecutor
     */
    public function getDTOLogicExecutor(): DTOLogicExecutor;

}