<?php

namespace Sidalex\Gateway\Classes\PsrRequestBuilder;

use Sidalex\Gateway\Classes\Config\EndpointProxyPoliticTypes;

class HostBuilder
{

     public function hostBuild(\stdClass $config): string
    {
        if ($config->endpointProxyPolitic == EndpointProxyPoliticTypes::TYPES[0]) {
            return $config->ProxyMainHost;
        }
        return "";
    }
}