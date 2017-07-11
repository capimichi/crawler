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
    /**
     * @inheritdoc
     */
    public function getContent()
    {
        $client = Client::getInstance();

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


}