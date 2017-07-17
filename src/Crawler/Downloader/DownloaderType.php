<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 17/07/17
 * Time: 21:27
 */

namespace Crawler\Downloader;

/**
 * Class DownloaderType
 * @package Crawler\Downloader
 */
abstract class DownloaderType
{
    const TYPE_SIMPLE = 0;

    const TYPE_CURL = 1;

    const TYPE_PHANTOM = 2;
}