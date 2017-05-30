<?php

namespace Rcm\VimeoData\Service;

use Interop\Container\ContainerInterface;
use Rcm\VimeoData\ModuleConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class VimeoApiFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        return new VimeoApi(
            $config[ModuleConfig::class]['apiClientId'],
            $config[ModuleConfig::class]['apiClientSecret'],
            $config[ModuleConfig::class]['apiClientAccessToken']
        );
    }
}
