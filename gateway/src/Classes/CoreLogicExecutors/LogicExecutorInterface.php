<?php

namespace Sidalex\Gateway\Classes\CoreLogicExecutors;

interface LogicExecutorInterface
{
    public function execute(DTOLogicExecutor $DTOLogicExecutor): DTOLogicExecutor;
}