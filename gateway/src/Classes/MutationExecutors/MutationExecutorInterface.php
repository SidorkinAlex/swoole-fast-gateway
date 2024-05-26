<?php

namespace Sidalex\Gateway\Classes\MutationExecutors;

interface MutationExecutorInterface
{
    public function mutation(mixed $mutationResource):mixed;
}