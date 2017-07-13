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
     * @var XpathQueryBuilder
     */
    protected $xpathQueryBuilder;

    /**
     * @var string
     */
    protected $url;

    /**
     * WebPage constructor.
     * @param \DOMXPath $xpath
     */
    public function __construct(\DOMXPath $xpath)
    {
        $this->xpathQueryBuilder = XpathQueryBuilder::createFromDomXpath($xpath);
    }

    /**
     * @return null|string
     */
    public function getMetaTitle()
    {
        $xpathQueryBuilder = $this->xpathQueryBuilder;
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
        $xpathQueryBuilder = $this->xpathQueryBuilder;
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

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
    

}