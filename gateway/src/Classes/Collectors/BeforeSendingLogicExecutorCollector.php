<?php

namespace Sidalex\Gateway\Classes\Collectors;

use Sidalex\Gateway\Classes\CoreLogicExecutors\DTOLogicExecutor;

class BeforeSendingLogicExecutorCollector extends AbstractSendingLogicExecutorCollector
{
    protected array $executorList = [];
    protected string $configName='BeforeSendingLogicExecutors';

}