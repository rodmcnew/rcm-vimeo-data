<?php

namespace Rcm\VimeoData\Service;

use Vimeo\Vimeo;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class VimeoApi
{
    /**
     * @var string
     */
    public $apiClientId;

    /**
     * @var string
     */
    public $apiClientSecret;

    /**
     * @var null|string
     */
    public $apiClientAccessToken;

    /**
     * @var Vimeo
     */
    public $vimeo;

    /**
     * @param string $apiClientId
     * @param string $apiClientSecret
     * @param string|null $apiClientAccessToken
     */
    public function __construct(
        $apiClientId,
        $apiClientSecret,
        $apiClientAccessToken = null
    ) {
        $this->apiClientId = $apiClientId;
        $this->apiClientSecret = $apiClientSecret;
        $this->apiClientAccessToken = $apiClientAccessToken;
        $this->vimeo = new Vimeo($apiClientId, $apiClientSecret, $apiClientAccessToken);
    }

    /**
     * @return bool
     */
    public function hasCredentials()
    {
        return (!empty($this->apiClientId) && !empty($this->apiClientSecret) && $this->hasAccessToken());
    }

    /**
     * @return bool
     */
    public function hasAccessToken()
    {
        return !empty($this->apiClientAccessToken);
    }

    /**
     * @param int $videoId
     *
     * @return array
     */
    public function getVideo($videoId)
    {
        $url = "/me/videos/{$videoId}";

        return $this->vimeo->request($url);
    }

    /**
     * @param $videoId
     * @param int $width
     * @return string|null
     * @throws \Exception
     */
    public function getDownloadLink($videoId, $width = 1920)
    {
        if (empty($videoId)) {
            return null;
        }

        if (!$this->hasCredentials()) {
            return null;
        }

        $response = $this->getVideo($videoId);

        if ($this->hasError($response)) {
            throw new \Exception('Response from Vimeo has error: ' . json_encode($response));
        }

        $body = $response['body'];

        if (!array_key_exists('download', $body)) {
            return null;
        }

        $linkData = $this->getLinkNearWidth($body['download'], $width);

        return $linkData['link'];
    }

    /**
     * @param array $links
     * @param int $width
     *
     * @return mixed|null
     * @throws \Exception
     */
    public function getLinkNearWidth(array $links, $width = 1920)
    {
        $selected = null;

        foreach ($links as $linkData) {

            if (empty($selected)) {
                $selected = $linkData;
            }

            $diffSelected = abs($selected['width'] - $width);
            $diffLinkData = abs($linkData['width'] - $width);

            if ($diffSelected > $diffLinkData) {
                $selected = $linkData;
            }
        }

        if (empty($selected)) {
            throw new \Exception("No width near {$width} found");
        }

        return $selected;
    }

    /**
     * @param array $response
     *
     * @return bool
     */
    public function hasError($response)
    {
        if (!is_array($response)) {
            return true;
        }

        if (!array_key_exists('body', $response)) {
            return true;
        }

        $body = $response['body'];

        if (array_key_exists('error', $body)) {
            return true;
        }

        return false;
    }
}
