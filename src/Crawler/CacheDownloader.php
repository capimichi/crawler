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
        return is_readable($this->getCacheFile());
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
        if (is_writable($this->getCacheFile())) {
            if (!file_exists($this->getExtendedCacheDirectory())) {
                $this->createCacheDirectory();
            }
            file_put_contents($this->getCacheFile(), $content);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    protected function createCacheDirectory()
    {
        return mkdir($this->getExtendedCacheDirectory(), 0777, true);
    }

}