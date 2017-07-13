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
        $phantomDownloader = new PhantomDownloader("http://www.google.com/");
        $content = $phantomDownloader->getContent();
        self::assertNotEmpty($content);
    }

}