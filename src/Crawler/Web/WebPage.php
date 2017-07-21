<?php

/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 13/07/17
 * Time: 23:30
 */

namespace Crawler\Web;

use Crawler\Downloader\CacheDownloader;
use Crawler\Downloader\DownloaderType;
use Crawler\Downloader\PhantomDownloader;
use Crawler\Downloader\SimpleDownloader;
use Crawler\XpathQueryBuilder;

class WebPage
{

    /**
     * @var string
     */
    protected $url;

    /**
     * @var CacheDownloader
     */
    protected $cacheDownloader;

    /**
     * The regex pattern to search in page
     * if found, it means that the website find out that you are a bot
     * else your successful crawled the page
     *
     * @var string|null
     */
    protected $botPattern;


    /**
     * WebPage constructor.
     * @param $url
     * @param $cacheDownloader
     */
    public function __construct($url, $cacheDownloader)
    {
        $this->url = $url;
        $this->cacheDownloader = $cacheDownloader;
        $this->botPattern = null;
    }


    /**
     * @return string
     */
    public function getContent()
    {
        return $this->cacheDownloader->getContent($this->url);
    }

    /**
     * @return \DOMDocument
     */
    public function getDomDocument()
    {
        return $this->cacheDownloader->getDomDocument($this->url);
    }

    /**
     * @return \DOMXPath
     */
    public function getDomXpath()
    {
        return $this->cacheDownloader->getDomXpath($this->url);
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
     * @return array
     */
    public function getClasses()
    {
        $classes = [];
        $content = $this->getContent();
        if (preg_match_all("//is", $content, $contentClasses)) {
            $contentClasses = $contentClasses[1];
            foreach ($contentClasses as $contentClass) {
                $splittedClasses = explode(' ', $contentClass);
                foreach ($splittedClasses as $splittedClass) {
                    array_push($classes, $splittedClass);
                }
            }
        }
        return array_count_values($classes);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isCrawlSuccessful()
    {
        if ($this->getBotPattern()) {
            return !preg_match($this->getBotPattern(), $this->getContent());
        } else {
            throw new \Exception("No bot pattern set, unknown crawl result");
        }
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return CacheDownloader
     */
    public function getCacheDownloader()
    {
        return $this->cacheDownloader;
    }

    /**
     * @param CacheDownloader $cacheDownloader
     */
    public function setCacheDownloader($cacheDownloader)
    {
        $this->cacheDownloader = $cacheDownloader;
    }

    /**
     * @return string
     */
    public function getBotPattern()
    {
        return $this->botPattern;
    }

    /**
     * @param string $botPattern
     */
    public function setBotPattern($botPattern)
    {
        $this->botPattern = $botPattern;
    }


}