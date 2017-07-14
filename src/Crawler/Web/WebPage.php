<?php

/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 13/07/17
 * Time: 23:30
 */

namespace Crawler\Web;

use Crawler\XpathQueryBuilder;

class WebPage
{

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $url;


    /**
     * WebPage constructor.
     * @param $url
     * @param $content
     */
    public function __construct($url, $content)
    {
        $this->url = $url;
        $this->content = $content;
    }


    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
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
     * @return XpathQueryBuilder
     */
    public function getXpathQueryBuilder()
    {
        return XpathQueryBuilder::createFromDomXpath($this->getDomXpath());
    }

    /**
     * @return null|string
     */
    public function getMetaTitle()
    {
        $xpathQueryBuilder = $this->getXpathQueryBuilder();
        $xpathQueryBuilder->addQueryBySelector('title');
        $titleNode = $xpathQueryBuilder->getResults();
        if ($titleNode->length) {
            $titleNode = $titleNode->item(0);
            $title = $titleNode->nodeValue;
            return $title;
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function getMetaDescription()
    {
        $xpathQueryBuilder = $this->getXpathQueryBuilder();
        $xpathQueryBuilder->addQueryByAttribute('name', 'description', 'meta');
        $descriptionNode = $xpathQueryBuilder->getResults();
        if ($descriptionNode->length) {
            $descriptionNode = $descriptionNode->item(0);
            $description = $descriptionNode->nodeValue;
            return $description;
        }
        return null;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }


}