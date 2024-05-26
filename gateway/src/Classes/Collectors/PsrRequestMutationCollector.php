<?php

namespace Sidalex\Gateway\Classes\Collectors;

use Sidalex\Gateway\Classes\MutationExecutors\MutationExecutorInterface;

class PsrRequestMutationCollector extends AbstractCollector implements CollectorInterface
{
    static string $CONFIG_TITLE = "PsrRequestMutationClass";

}