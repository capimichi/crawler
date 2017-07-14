<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 11/07/17
 * Time: 17:56
 */

namespace Crawler\Downloader;

abstract class Downloader
{

    const DEFAULT_USERAGENT = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36';

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $userAgent;

    /**
     * Downloader constructor.
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Download the content of the url
     *
     * @return mixed
     */
    public abstract function getContent();

    /**
     * Get the DOMDocument of the downloaded content
     *
     * @return \DOMDocument
     */
    public abstract function getDomDocument();

    /**
     * Get the DOMXPath of the downloaded content
     *
     * @return \DOMXPath
     */
    public abstract function getDomXpath();

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }
}