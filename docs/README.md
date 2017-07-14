# Crawler

Use this library to scrape your favourite websites.

## Get started
```php
<?php

use Crawler\Downloader\PhantomDownloader;

$phantomDownloader = new PhantomDownloader("https://capimichi.github.io/crawler/test/download.json");
```

### You can even set up cache
```php
<?php

use Crawler\Downloader\PhantomDownloader;
use Crawler\CacheDownloader;

$phantomDownloader = new PhantomDownloader("https://capimichi.github.io/crawler/test/download.json");
$cacheDownloader = new CacheDownloader($phantomDownloader, "/path/to/cache/directory");
```