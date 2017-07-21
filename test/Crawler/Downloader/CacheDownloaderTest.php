<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 11/07/17
 * Time: 17:56
 */

namespace Crawler\Test\Downloader;


use Crawler\Downloader\CacheDownloader;
use Crawler\Downloader\PhantomDownloader;
use Crawler\Downloader\SimpleDownloader;
use PHPUnit\Framework\TestCase;

class CacheDownloaderTest extends TestCase
{

    const ROOT_DIRECTORY = __DIR__ . '/../../../';

    /**
     * Test if can create cache file
     */
    public function testCanCreateCache()
    {
        $rootDirectory = realpath(CacheDownloaderTest::ROOT_DIRECTORY);
        $cacheDirectory = $rootDirectory . "/var/cache/";
        $url = "https://capimichi.github.io/crawler/test/big.html";
        $downloader = new SimpleDownloader();
        $cacheDownloader = new CacheDownloader($downloader, $cacheDirectory);
        $cacheDownloader->getContent($url);
        $this->assertFileExists($cacheDownloader->getCacheFile($url));
    }

    /**
     * Test if can cache data
     */
    public function testCanCache()
    {
        $rootDirectory = realpath(CacheDownloaderTest::ROOT_DIRECTORY);
        $cacheDirectory = $rootDirectory . "/var/cache/";
        $url = "https://capimichi.github.io/crawler/test/big.html";
        $downloader = new SimpleDownloader();
        $cacheDownloader = new CacheDownloader($downloader, $cacheDirectory);
        $cacheDownloader->getContent($url);
        $cacheDownloader->getDomDocument($url);
        $cacheDownloader->getDomXpath($url);

        $content = $cacheDownloader->getContent($url);
        $this->assertNotEmpty($content);
        $dom = $cacheDownloader->getDomDocument($url);
        $this->assertNotEmpty($dom);
        $xpath = $cacheDownloader->getDomXpath($url);
        $this->assertNotEmpty($xpath);
        $scriptNodes = $xpath->query('//script');
        $this->assertEquals(1, $scriptNodes->length);
    }

    /**
     * Handle a lot of cache files
     */
    public function testCanHandleLotsCache()
    {

    }

}