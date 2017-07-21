<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 11/07/17
 * Time: 17:56
 */

namespace Crawler\Test\Downloader;


use Crawler\Downloader\PhantomDownloader;
use PHPUnit\Framework\TestCase;

class PhantomDownloaderTest extends TestCase
{

    /**
     * Test if downloader is working
     */
    public function testCanDownload()
    {
        $url = "https://capimichi.github.io/crawler/test/download.json";
        $phantomDownloader = new PhantomDownloader();

        $content = $phantomDownloader->getContent($url);

        self::assertNotEmpty($content);
    }

}