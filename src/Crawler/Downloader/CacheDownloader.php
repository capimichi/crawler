<?php

/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 11/07/17
 * Time: 18:09
 */

namespace Crawler\Downloader;

use Cache\Cache;

class CacheDownloader
{
    const DEFAULT_MIN_CACHE_SIZE = 0;

    /**
     * @var Downloader
     */
    protected $downloader;

    /**
     * @var string
     */
    protected $cacheDirectory;

    /**
     * @var string
     */
    protected $cacheExtension;

    /**
     * @var float
     */
    protected $minCacheSize;

    /**
     * @var array
     */
    protected $strippedHtmlTags;


    /**
     * CacheDownloader constructor.
     * @param Downloader $downloader
     * @param $cacheDirectory
     * @param $cacheExtension
     * @throws \Exception
     */
    public function __construct(Downloader $downloader, $cacheDirectory, $cacheExtension = ".cache")
    {
        $this->downloader = $downloader;
        $this->cacheDirectory = rtrim($cacheDirectory, "/") . "/";
        $this->cacheExtension = $cacheExtension;
        $this->minCacheSize = 0;
        $this->strippedHtmlTags = [];
    }


    /**
     * @param $url
     *
     * @return bool|mixed|string
     */
    public function getContent($url)
    {
        $cache = $this->getCache($url);
        if ($cache->isCached('content')) {
            $content = $cache->retrieve('content');
        } else {
            $content = $this->downloader->getContent($url);
            $cache->store('content', $content);
        }
        return $content;
    }

    /**
     * @param $url
     *
     * @return \DOMDocument
     */
    public function getDomDocument($url)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($this->getContent($url));
        return $dom;
    }

    /**
     * @param $url
     *
     * @return \DOMXPath
     */
    public function getDomXpath($url)
    {
        $xpath = new \DOMXPath($this->getDomDocument($url));
        return $xpath;
    }

    /**
     * @param $url
     *
     * @return bool
     */
    public function isCached($url)
    {
        return file_exists($this->getCache($url)->getCacheFile());
    }

    /**
     * @param $url
     *
     * @return string
     */
    public function getCacheFile($url)
    {
        return $this->getCache($url)->getCacheFile();
    }

    /**
     * @return string
     */
    public function getCacheDirectory()
    {
        return $this->cacheDirectory;
    }

    /**
     * @param $url
     *
     * @return string
     */
    public function getExtendedCacheDirectory($url)
    {
        return $this->getCache($url)->getDirectory();
    }

    /**
     * @param $url
     *
     * @return string
     */
    public function getCacheName($url)
    {
        return Cache::generateCacheKey($url);
    }

    /**
     * @return string
     */
    public function getCacheExtension()
    {
        return $this->cacheExtension;
    }

    /**
     * @return Downloader
     */
    public function getDownloader()
    {
        return $this->downloader;
    }

    /**
     * @param string $cacheExtension
     */
    public function setCacheExtension($cacheExtension)
    {
        $this->cacheExtension = $cacheExtension;
    }

    /**
     * @return float
     */
    public function getMinCacheSize()
    {
        return $this->minCacheSize;
    }

    /**
     * @param float $minCacheSize
     */
    public function setMinCacheSize($minCacheSize)
    {
        $this->minCacheSize = $minCacheSize;
    }

    /**
     * @param $strippedHtmlTag
     */
    public function addStrippedHtmlTag($strippedHtmlTag)
    {
        array_push($this->strippedHtmlTags, $strippedHtmlTag);
        $this->strippedHtmlTags = array_unique($this->strippedHtmlTags);
    }

    /**
     * @return array
     */
    public function getStrippedHtmlTags()
    {
        return $this->strippedHtmlTags;
    }

    /**
     * @param array $strippedHtmlTags
     */
    public function setStrippedHtmlTags($strippedHtmlTags)
    {
        $this->strippedHtmlTags = $strippedHtmlTags;
    }

    /**
     * @param $url
     * @return Cache
     */
    protected function getCache($url)
    {
        $cacheKey = Cache::generateCacheKey($url);
        $cache = new Cache($cacheKey, $this->getCacheDirectory(), $this->getCacheExtension());
        $cache->setExtendedPath(true);
        return $cache;
    }

}