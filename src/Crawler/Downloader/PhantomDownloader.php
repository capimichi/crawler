<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 11/07/17
 * Time: 17:56
 */

namespace Crawler\Downloader;


use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\Http\Request;

class PhantomDownloader extends Downloader
{

    const PHANTOMJS_PATH = __DIR__ . "/../../../bin/phantomjs";

    const DEFAULT_USERAGENT = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36';

    /**
     * @var string
     */
    protected $phantomjsPath;

    /**
     * @var string
     */
    protected $userAgent;

    public function __construct($url)
    {
        $this->setPhantomjsPath(realpath(self::PHANTOMJS_PATH));
        $this->setUserAgent(self::DEFAULT_USERAGENT);

        parent::__construct($url);
    }

    /**
     * @inheritdoc
     */
    public function getContent()
    {
        $client = Client::getInstance();

        $client->getEngine()->setPath($this->getPhantomjsPath());

//        $request = $client->getMessageFactory()->createRequest($this->url, 'GET');

        $request = new Request($this->url, 'GET');

        $request->addSetting('userAgent', $this->getUserAgent());

        $response = $client->getMessageFactory()->createResponse();

        $client->send($request, $response);

        return $response->getContent();
    }

    /**
     * @inheritdoc
     */
    public function getDomDocument()
    {
        $content = $this->getContent();
        $dom = new \DOMDocument();
        @$dom->loadHTML($content);
        return $dom;
    }

    /**
     * @inheritdoc
     */
    public function getDomXpath()
    {
        $dom = $this->getDomDocument();
        $xpath = new \DOMXPath($dom);
        return $xpath;
    }

    /**
     * @return string
     */
    public function getPhantomjsPath()
    {
        return $this->phantomjsPath;
    }

    /**
     * @param string $phantomjsPath
     */
    public function setPhantomjsPath($phantomjsPath)
    {
        $this->phantomjsPath = $phantomjsPath;
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