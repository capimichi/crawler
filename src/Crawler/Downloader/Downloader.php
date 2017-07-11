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

    /**
     * @var string
     */
    protected $url;

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
}