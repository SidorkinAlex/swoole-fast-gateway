<?php

namespace Sidalex\Gateway\Classes\Config;

class ConfigValidator
{
    /**
     * @param \stdClass $config
     * @return \stdClass
     * @throws ConfigException
     */
    public static function Validate(\stdClass $config): \stdClass
    {
//        echo '$config->endpointProxyPolitic=';
//        echo $config->endpointProxyPolitic;
//        echo "\n";
//        var_export(EndpointProxyPoliticTypes::TYPES);
        if (!isset($config->endpointProxyPolitic)) {
            throw new ConfigException('endpoint Proxy Politic is required, but not set. specify one of the acceptable parameters.',1100);
        }
        if (!is_string($config->endpointProxyPolitic )) {
            throw new ConfigException('endpoint Proxy Politic is not string. specify one of the acceptable parameters.',1101);
        }
        if (!in_array($config->endpointProxyPolitic,EndpointProxyPoliticTypes::TYPES )) {
            throw new ConfigException('endpoint Proxy Politic has not valid value. specify one of the acceptable parameters.',1102);
        }

        return $config;
    }

}