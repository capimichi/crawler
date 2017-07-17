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

    const DEFAULT_PHANTOMJS_PATH = __DIR__ . "/../../../bin/phantomjs";

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

    public function __construct($url)
    {
        $this->phantomjsPath = realpath(PhantomDownloader::DEFAULT_PHANTOMJS_PATH);
        $this->loadImages = PhantomDownloader::DEFAULT_LOAD_IMAGES;
        $this->javascriptEnabled = PhantomDownloader::DEFAULT_JAVASCRIPT_ENABLED;
        $this->webSecurityEnabled = PhantomDownloader::DEFAULT_WEB_SECURITY_ENABLED;
        $this->debug = PhantomDownloader::DEFAULT_DEBUG;
        $this->logs = [];

        parent::__construct($url);
    }

    /**
     * @inheritdoc
     */
    public function getContent()
    {
        $client = Client::getInstance();

        $client->getEngine()->setPath($this->getPhantomjsPath());

        $request = new Request($this->url, 'GET');

        if ($this->randomUserAgent) {
            $userAgentList = Downloader::USER_AGENT_LIST;
            $key = array_rand($userAgentList);
            $userAgent = $userAgentList[$key];
            $request->addSetting('userAgent', $userAgent);
        } else {
            $request->addSetting('userAgent', $this->getUserAgent());
        }

        $request->addSetting('javascriptEnabled', $this->isJavascriptEnabled());

        $request->addSetting('loadImages', $this->isLoadImages());

        $request->addSetting('webSecurityEnabled', $this->isWebSecurityEnabled());

        $request->setTimeout($this->getTimeout());

        $response = $client->getMessageFactory()->createResponse();

        $client->send($request, $response);

        if ($this->isDebug()) {
            $this->addLog($client->getLog());
        }

        return $response->getContent();
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