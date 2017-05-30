<?php

namespace Rcm\VimeoData\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\VimeoData\Service\VimeoApi;
use Zend\Expressive\Router\RouteResult;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Download
{
    /**
     * @var VimeoApi
     */
    public $vimeoApi;

    /**
     * @param VimeoApi $vimeoApi
     */
    public function __construct(
        VimeoApi $vimeoApi
    ) {
        $this->vimeoApi = $vimeoApi;
    }

    /**
     * @param array  $params
     * @param string $key
     * @param null   $default
     *
     * @return null|mixed
     */
    protected function getParam(array $params, $key, $default = null)
    {
        if (array_key_exists($key, $params)) {
            return $params[$key];
        }

        return $default;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $out
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null)
    {
        $videoId = $request->getAttribute('videoId', null);

        $width = (int)$this->getParam(
            $request->getQueryParams(),
            'width',
            1920
        );

        if (empty($videoId)) {
            return $response->withStatus(404);
        }

        $link = $this->vimeoApi->getDownloadLink($videoId, $width);

        if (empty($link)) {
            return $response->withStatus(404);
        }

        return $response->withStatus(302)->withHeader(
            'Location',
            $link
        );
    }
}
