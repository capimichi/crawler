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
    const USER_AGENT_LIST = [
        'Firefox 40.1'       => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1',
        'Firefox 36.0'       => 'Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0',
        'Firefox 33.0'       => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10; rv:33.0) Gecko/20100101 Firefox/33.0',
        'Firefox 33.0'       => 'Mozilla/5.0 (X11; Linux i586; rv:31.0) Gecko/20100101 Firefox/31.0',
        'Firefox 29.0'       => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:29.0) Gecko/20120101 Firefox/29.0',
        'Chrome 41.0.2228.0' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36',
        'Chrome 41.0.2227.1' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.1 Safari/537.36',
        'Chrome 41.0.2227.0' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36',
        'Chrome 41.0.2226.0' => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2226.0 Safari/537.36',
        'Chrome 41.0.2225.0' => 'Mozilla/5.0 (Windows NT 6.4; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2225.0 Safari/537.36',
        'Chrome 41.0.2224.3' => 'http://www.useragentstring.com/index.php?id=19837',
        'Opera 12.16'        => 'Opera/9.80 (X11; Linux i686; Ubuntu/14.10) Presto/2.12.388 Version/12.16',
        'Opera 12.14'        => 'Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14',
        'Opera 12.02'        => 'Opera/12.80 (Windows NT 5.1; U; en) Presto/2.10.289 Version/12.02',
        'Opera 12.00'        => 'Opera/9.80 (Windows NT 6.1; U; es-ES) Presto/2.9.181 Version/12.00',
        'Opera 11.62'        => 'Opera/9.80 (Windows NT 6.1; WOW64; U; pt) Presto/2.10.229 Version/11.62',
    ];

    const DEFAULT_USERAGENT = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36';

    const DEFAULT_TIMEOUT = 5000;

    const DEFAULT_RANDOM_USERAGENT = false;

    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @var bool
     */
    protected $randomUserAgent;

    /**
     * Downloader constructor.
     */
    public function __construct()
    {
        $this->userAgent = Downloader::DEFAULT_USERAGENT;
        $this->timeout = Downloader::DEFAULT_TIMEOUT;
        $this->randomUserAgent = Downloader::DEFAULT_RANDOM_USERAGENT;
    }

    /**
     * Download the content of the url
     *
     * @param $url
     *
     * @return mixed
     */
    public abstract function getContent($url);

    /**
     * Get the DOMDocument of the downloaded content
     *
     * @param $url
     *
     * @return \DOMDocument
     */
    public function getDomDocument($url)
    {
        $content = $this->getContent($url);
        $dom = new \DOMDocument();
        @$dom->loadHTML($content);
        return $dom;
    }

    /**
     * Get the DOMXPath of the downloaded content
     *
     * @param $url
     *
     * @return \DOMXPath
     */
    public function getDomXpath($url)
    {
        $dom = $this->getDomDocument($url);
        $xpath = new \DOMXPath($dom);
        return $xpath;
    }

    /**
     * @return string
     */
    public
    function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public
    function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return int
     */
    public
    function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     */
    public
    function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @return bool
     */
    public
    function isRandomUserAgent()
    {
        return $this->randomUserAgent;
    }

    /**
     * @param bool $randomUserAgent
     */
    public
    function setRandomUserAgent($randomUserAgent)
    {
        $this->randomUserAgent = $randomUserAgent;
    }


}