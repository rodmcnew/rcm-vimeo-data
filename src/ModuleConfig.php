<?php
namespace Rcm\VimeoData;

use Rcm\VimeoData\Middleware\Download;
use Rcm\VimeoData\Middleware\DownloadFactory;
use Rcm\VimeoData\Service\VimeoApi;
use Rcm\VimeoData\Service\VimeoApiFactory;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            static::class => [
                'apiClientId' => '',
                'apiClientSecret' => '',
                'apiClientAccessToken' => '',
            ],
            'dependencies' => [
                'factories' => [
                    Download::class => DownloadFactory::class,
                    VimeoApi::class => VimeoApiFactory::class,
                ],
            ],
            'routes' => [
                '/vimeo/video/download/:videoId' =>
                [
                    'path' => '/vimeo/video/download/:videoId',
                    'middleware' => Download::class,
                    'allowed_methods' => ['GET'],
                ],
            ]
        ];
    }
}
