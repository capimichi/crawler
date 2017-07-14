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
    protected $minimumCacheSize;

    /**
     * @var int
     */
    protected $maxRetries;


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
        $this->minimumCacheSize = 0;
        $this->maxRetries = 5;
    }


    /**
     * @return bool|mixed|string
     */
    public function getContent()
    {
        if ($this->isCached()) {
            $content = $this->getCache();
        } else {
            $retries = 0;
            do {
                $retries++;
                $content = $this->downloader->getContent();
                $this->setCache($content);
            } while (!$this->isCached() && $retries <= $this->getMaxRetries());
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
            return $this->getMinimumCacheSize() ? ($this->getCacheSize() >= $this->getMinimumCacheSize()) : true;
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
    public function getMinimumCacheSize()
    {
        return $this->minimumCacheSize;
    }

    /**
     * @param float $minimumCacheSize
     */
    public function setMinimumCacheSize($minimumCacheSize)
    {
        $this->minimumCacheSize = $minimumCacheSize;
    }

    /**
     * @return int
     */
    public function getMaxRetries()
    {
        return $this->maxRetries;
    }

    /**
     * @param int $maxRetries
     */
    public function setMaxRetries($maxRetries)
    {
        $this->maxRetries = $maxRetries;
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