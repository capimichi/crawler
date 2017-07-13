<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 11/07/17
 * Time: 17:56
 */

namespace Crawler\Downloader;


use JonnyW\PhantomJs\Client;

class PhantomDownloader extends Downloader
{

    const PHANTOMJS_PATH = __DIR__ . "/../../../bin/phantomjs";

    /**
     * @var string
     */
    protected $phantomjsPath;

    public function __construct($url)
    {
        $this->setPhantomjsPath(realpath(self::PHANTOMJS_PATH));

        parent::__construct($url);
    }

    /**
     * @inheritdoc
     */
    public function getContent()
    {
        $client = Client::getInstance();

        $client->getEngine()->setPath($this->getPhantomjsPath());

        $request = $client->getMessageFactory()->createRequest($this->url, 'GET');

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


}