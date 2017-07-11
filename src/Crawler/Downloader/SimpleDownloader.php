<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 11/07/17
 * Time: 17:56
 */

namespace Crawler\Downloader;


class SimpleDownloader extends Downloader
{
    /**
     * @inheritdoc
     */
    public function getContent()
    {
        $content = file_get_contents($this->url);
        return $content;
    }


}