<?php

namespace Sidalex\Gateway\Classes\Collectors;

use Sidalex\Gateway\Classes\MutationExecutors\MutationExecutorInterface;

abstract class AbstractCollector implements MutationCollectorInterface, CollectorInterface
{

    /**
     * @var array<MutationExecutorInterface>
     */
    protected array $mutationList = [];
    static string $CONFIG_TITLE = "";

    public function __construct(\stdClass $config)
    {
        if (isset($config->{static::$CONFIG_TITLE}) && !empty($config->{static::$CONFIG_TITLE})) {
            foreach ($config->{static::$CONFIG_TITLE} as $className) {
                $mutationObj = new $className;
                if ($mutationObj instanceof MutationExecutorInterface) {
                    $this->mutationList[] = $mutationObj;
                }
            }
        }
    }

    public function getCountMutationExecutors(): int
    {
        return count($this->mutationList);
    }

    /**
     * @return array|MutationExecutorInterface[]
     */
    public function getMutationExecutors(): array
    {
        return $this->mutationList;
    }


}