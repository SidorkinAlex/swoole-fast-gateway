<?php

namespace Sidalex\Gateway\Classes\Collectors;

use Sidalex\Gateway\Classes\CoreLogicExecutors\DTOLogicExecutor;

class AfterSendingLogicExecutorCollector extends AbstractSendingLogicExecutorCollector
{
    protected array $executorList = [];
    protected string $configName='AfterSendingLogicExecutors';
}