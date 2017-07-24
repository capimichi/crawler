<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 11/07/17
 * Time: 17:56
 */

namespace Crawler\Downloader;


use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\Http\Request;

class PhantomDownloader extends Downloader
{

    const BIN_DIR = __DIR__ . "/../../../bin/";

    const DOWNLOAD_SCRIPT = self::BIN_DIR . "download.js";

    const DEFAULT_LOAD_IMAGES = true;

    const DEFAULT_JAVASCRIPT_ENABLED = true;

    const DEFAULT_WEB_SECURITY_ENABLED = true;

    const DEFAULT_DEBUG = false;

    /**
     * @var string
     */
    protected $phantomjsPath;

    /**
     * @var bool
     */
    protected $loadImages;

    /**
     * @var bool
     */
    protected $webSecurityEnabled;

    /**
     * @var bool
     */
    protected $javascriptEnabled;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var array
     */
    protected $logs;

    public function __construct()
    {
        $this->phantomjsPath = realpath(PhantomDownloader::BIN_DIR) . "/phantomjs";
        $this->loadImages = PhantomDownloader::DEFAULT_LOAD_IMAGES;
        $this->javascriptEnabled = PhantomDownloader::DEFAULT_JAVASCRIPT_ENABLED;
        $this->webSecurityEnabled = PhantomDownloader::DEFAULT_WEB_SECURITY_ENABLED;
        $this->debug = PhantomDownloader::DEFAULT_DEBUG;
        $this->logs = [];

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function getContent($url)
    {

        $command = sprintf("%s %s \"%s\" %d %d %d \"%s\" %d",
            $this->getPhantomjsPath(),
            realpath(PhantomDownloader::DOWNLOAD_SCRIPT),
            $url,
            $this->getTimeout(),
            $this->isJavascriptEnabled(),
            $this->isLoadImages(),
            $this->getUserAgent(),
            $this->isWebSecurityEnabled()
        );
        exec($command, $content);
        $content = implode("", $content);
        return $content;
    }

    /**
     * @return string
     */
    public function getPhantomjsPath()
    {
        return $this->phantomjsPath;
    }

    /**
     * @param string $phantomjsPath
     */
    public function setPhantomjsPath($phantomjsPath)
    {
        $this->phantomjsPath = $phantomjsPath;
    }

    /**
     * @return bool
     */
    public function isLoadImages()
    {
        return $this->loadImages;
    }

    /**
     * @param bool $loadImages
     */
    public function setLoadImages($loadImages)
    {
        $this->loadImages = $loadImages;
    }

    /**
     * @return bool
     */
    public function isWebSecurityEnabled()
    {
        return $this->webSecurityEnabled;
    }

    /**
     * @param bool $webSecurityEnabled
     */
    public function setWebSecurityEnabled($webSecurityEnabled)
    {
        $this->webSecurityEnabled = $webSecurityEnabled;
    }

    /**
     * @return bool
     */
    public function isJavascriptEnabled()
    {
        return $this->javascriptEnabled;
    }

    /**
     * @param bool $javascriptEnabled
     */
    public function setJavascriptEnabled($javascriptEnabled)
    {
        $this->javascriptEnabled = $javascriptEnabled;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * @return array
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param $log
     */
    protected function addLog($log)
    {
        array_push($this->logs, $log);
    }


}