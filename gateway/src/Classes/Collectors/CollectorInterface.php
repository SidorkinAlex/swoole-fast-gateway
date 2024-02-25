<?php

namespace Sidalex\Gateway\Classes\Collectors;

use Sidalex\Gateway\Classes\MutationExecutors\MutationExecutorInterface;

interface CollectorInterface
{

    public function getCountMutationExecutors(): int;

    /**
     * @return array|MutationExecutorInterface[]
     */
    public function getMutationExecutors(): array;
}