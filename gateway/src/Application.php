<?php

namespace Sidalex\Gateway;

use HaydenPierce\ClassFinder\ClassFinder;
use Sidalex\Gateway\Classes\Cache\CacheRequestCollector;
use Sidalex\Gateway\Classes\Cache\ResponseCache;
use Sidalex\Gateway\Classes\Client\Requester;
use Sidalex\Gateway\Classes\Collectors\SwooleRequestMutationCollector;
use Sidalex\Gateway\Classes\Config\ConfigException;
use Sidalex\Gateway\Classes\Config\ConfigValidator;
use Sidalex\Gateway\Classes\CoreLogicExecutors\LogicExecutorInterface;
use stdClass;

class Application
{
    private stdClass $config;

    private CacheRequestCollector $repositoryCache;


    public function __construct()
    {
        $this->initConfig();
        $this->repositoryCache = new CacheRequestCollector($this->config);
    }

    private function initConfig(): void
    {
        $content = file_get_contents(__DIR__ . "/../config.json");
        if($content === false){
            echo "config.json not exist or is not readable";
            exit(1);
        }
        $config_body = json_decode($content);
        unset($content);
        if($config_body === false){
         echo "config.json is not set or is not valid JSON";
            exit(1);
        }
        $this->config=$config_body;
        unset($config_body);
        try {
            $this->config = ConfigValidator::Validate($this->config);
        } catch (ConfigException $e) {
            echo $e->getMessage() . " error code is" . $e->getCode();
            exit(1);
        }
        $this->add2Config();
    }
    private function add2Config(): void
    {
        $namespace = [
            'AfterSendingLogicExecutors' => 'Sidalex\Gateway\Classes\AfterSendingLogicExecutors',
            'BeforeSendingLogicExecutors' => 'Sidalex\Gateway\Classes\BeforeSendingLogicExecutors',
        ];
        try {
            $classes = ClassFinder::getClassesInNamespace($namespace['AfterSendingLogicExecutors']);
            foreach ($classes as $class) {
                $object = new $class();
                if ($object instanceof LogicExecutorInterface) {
                    $this->config->AfterSendingLogicExecutors[] = $class;
                }
                unset($object);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit(1);
        }

        try {
            $classes = ClassFinder::getClassesInNamespace($namespace['BeforeSendingLogicExecutors']);
            foreach ($classes as $class) {
                $object = new $class();
                if ($object instanceof LogicExecutorInterface) {
                    $this->config->BeforeSendingLogicExecutors[] = $class;
                }
                unset($object);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit(1);
        }
    }


}