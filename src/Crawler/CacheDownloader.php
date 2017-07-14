<?php

/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 11/07/17
 * Time: 18:09
 */

namespace Crawler;

use Crawler\Downloader\Downloader;

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
     * @return bool|mixed|string
     */
    public function getContent()
    {
        if ($this->isCached()) {
            $content = $this->getCache();
        } else {
            $content = $this->downloader->getContent();
            $this->setCache($content);
        }
        return $content;
    }

    /**
     * @return \DOMDocument
     */
    public function getDomDocument()
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($this->getContent());
        return $dom;
    }

    /**
     * @return \DOMXPath
     */
    public function getDomXpath()
    {
        $xpath = new \DOMXPath($this->getDomDocument());
        return $xpath;
    }

    /**
     * @return bool
     */
    public function isCached()
    {
        if (is_readable($this->getCacheFile())) {
            $isValidCache = true;
            if ($this->getMinCacheSize()) {
                if ($this->getCacheSize() < $this->getMinCacheSize()) {
                    $isValidCache = false;
                }
            }
            return $isValidCache;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getCacheFile()
    {
        return $this->getExtendedCacheDirectory() . $this->getCacheName() . $this->getCacheExtension();
    }

    /**
     * @return string
     */
    public function getCacheDirectory()
    {
        return $this->cacheDirectory;
    }

    /**
     * @return string
     */
    public function getExtendedCacheDirectory()
    {
        $dir = $this->getCacheDirectory();
        for ($i = 0; $i <= 4; $i += 2) {
            $dir .= substr($this->getCacheName(), $i, 2) . DIRECTORY_SEPARATOR;
        }
        return $dir;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->downloader->getUrl();
    }

    /**
     * @return string
     */
    public function getCacheName()
    {
        return md5($this->getUrl());
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
     * @return int
     */
    protected function getCacheSize()
    {
        return filesize($this->getCacheFile()) / 1024;
    }

    /**
     * @return bool|string
     */
    protected function getCache()
    {
        return file_get_contents($this->getCacheFile());
    }

    /**
     * @param $content
     * @return bool
     */
    protected function setCache($content)
    {

        if (!$this->isPresentCacheDirectory()) {
            if (!$this->createCacheDirectory()) {
                return false;
            }
        }

        foreach ($this->getStrippedHtmlTags() as $strippedHtmlTag) {
            $pattern = sprintf("/<%s>.*?<\/%s>/is", $strippedHtmlTag, $strippedHtmlTag);
            $content = preg_replace($pattern, "", $content);
        }

        file_put_contents($this->getCacheFile(), $content);
        return true;
    }

    /**
     * @return bool
     */
    protected function isPresentCacheDirectory()
    {
        return file_exists($this->getExtendedCacheDirectory());
    }

    /**
     * @return bool
     */
    protected function createCacheDirectory()
    {
        return mkdir($this->getExtendedCacheDirectory(), 0777, true);
    }

}