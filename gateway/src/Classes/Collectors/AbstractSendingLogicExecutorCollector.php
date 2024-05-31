<?php

namespace Sidalex\Gateway\Classes\Collectors;

use GuzzleHttp\Psr7\Request;
use HaydenPierce\ClassFinder\ClassFinder;
use Sidalex\Gateway\Classes\Cache\CacheRequestCollector;
use Sidalex\Gateway\Classes\CoreLogicExecutors\DTOLogicExecutor;
use Sidalex\Gateway\Classes\CoreLogicExecutors\LogicExecutorInterface;

abstract class AbstractSendingLogicExecutorCollector implements SendingExecutorCollectorInterface
{
    /**
     * @var array|LogicExecutorInterface[]
     */
    protected array $executorList = [];
    private DTOLogicExecutor $DTOLogicExecutor;
//    protected string $namespace = '';
//    protected array $classes =[];
    protected string $configName;

    public function __construct(
        DTOLogicExecutor $DTOLogicExecutor
    )
    {
        $this->DTOLogicExecutor = $DTOLogicExecutor;
        try {
            foreach ($this->DTOLogicExecutor->getConfig()->{$this->configName} as $class) {
                $object = new $class();
                if ($object instanceof LogicExecutorInterface) {
                    $this->executorList[] = $object;
                }
            }


        } catch (\Exception $e) {
            echo $e->getMessage();
            // TODO сделать нормальную обработку эксепшенов
        }
    }

    public function getCountMutationExecutors(): int
    {
        return count($this->executorList);
    }

    public function executeCollection(): void
    {
        foreach ($this->executorList as $executor) {
            if ($executor instanceof LogicExecutorInterface) {
                $this->DTOLogicExecutor = $executor->execute($this->DTOLogicExecutor);
            }
        }
    }

    /**
     * @return DTOLogicExecutor
     */
    public function getDTOLogicExecutor(): DTOLogicExecutor
    {
        return $this->DTOLogicExecutor;
    }


}