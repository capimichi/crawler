# Crawler

Use this library to scrape your favourite websites.

## Get started
```php
<?php

use Crawler\Downloader\CacheDownloader;
use Crawler\Downloader\SimpleDownloader;

require_once "/path/to/composer/autoload.php";

$startUrl = "http://random-url.com/";

$downloader = new SimpleDownloader();
$cacheDownloader = new CacheDownloader($downloader, __DIR__ . "/var/cache/", ".html");
$mainCategoriesWebPage = new MainCategoriesWebPage($startUrl, $cacheDownloader);

$categoryUrls = $mainCategoriesWebPage->getCategoryUrls();

foreach ($categoryUrls as $categoryUrl) {

    do {
        $productsWebPage = new ProductsWebPage($categoryUrl, $cacheDownloader);

        $productUrls = $productsWebPage->getChildUrls();

        foreach ($productUrls as $productUrl) {
            $productWebPage = new ProductWebPage($productUrl, $cacheDownloader);
            $title = $productWebPage->getTitle();
        }

        $categoryUrl = $productsWebPage->getNextPageUrl();
    } while ($categoryUrl != null);
}
```
