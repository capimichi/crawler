<?php

/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 11/07/17
 * Time: 18:09
 */

namespace Crawler\Downloader;

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
        if ($this->isCached($url)) {
            $content = $this->getCache($url);
        } else {
            $content = $this->downloader->getContent($url);
            $this->setCache($url, $content);
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
        if (is_readable($this->getCacheFile($url))) {
            $isValidCache = true;
            if ($this->getMinCacheSize()) {
                if ($this->getCacheSize($url) < $this->getMinCacheSize()) {
                    $isValidCache = false;
                }
            }
            return $isValidCache;
        }
        return false;
    }

    /**
     * @param $url
     *
     * @return string
     */
    public function getCacheFile($url)
    {
        return $this->getExtendedCacheDirectory($url) . $this->getCacheName($url) . $this->getCacheExtension();
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
        $dir = $this->getCacheDirectory();
        for ($i = 0; $i <= 4; $i += 2) {
            $dir .= substr($this->getCacheName($url), $i, 2) . DIRECTORY_SEPARATOR;
        }
        return $dir;
    }

    /**
     * @param $url
     *
     * @return string
     */
    public function getCacheName($url)
    {
        return md5($url);
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
     *
     * @return int
     */
    protected function getCacheSize($url)
    {
        return filesize($this->getCacheFile($url)) / 1024;
    }

    /**
     * @param $url
     *
     * @return bool|string
     */
    protected function getCache($url)
    {
        return file_get_contents($this->getCacheFile($url));
    }

    /**
     * @param $url
     * @param $content
     *
     * @return bool
     */
    protected function setCache($url, $content)
    {

        if (!$this->isPresentCacheDirectory($url)) {
            if (!$this->createCacheDirectory($url)) {
                return false;
            }
        }

        foreach ($this->getStrippedHtmlTags() as $strippedHtmlTag) {
            $pattern = sprintf("/<%s>.*?<\/%s>/is", $strippedHtmlTag, $strippedHtmlTag);
            $content = preg_replace($pattern, "", $content);
        }

        file_put_contents($this->getCacheFile($url), $content);
        return true;
    }

    /**
     * @param $url
     *
     * @return bool
     */
    protected function isPresentCacheDirectory($url)
    {
        return file_exists($this->getExtendedCacheDirectory($url));
    }

    /**
     * @param $url
     *
     * @return bool
     */
    protected function createCacheDirectory($url)
    {
        return mkdir($this->getExtendedCacheDirectory($url), 0777, true);
    }

}