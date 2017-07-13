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


}