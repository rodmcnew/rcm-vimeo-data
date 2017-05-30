<?php

namespace Rcm\VimeoData\Middleware;

use Interop\Container\ContainerInterface;
use Rcm\VimeoData\Service\VimeoApi;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class DownloadFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return Download
     */
    public function __invoke(ContainerInterface $container)
    {
        return new Download(
            $container->get(VimeoApi::class)
        );
    }
}
