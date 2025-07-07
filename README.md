# idsqm/php-sitemap

## Description
A library for generating and exporting Sitemaps in xml, json, and csv formats. It is possible to register your own formats

## Usage example
Generation sitemap.xml
```php
<?php

use Carbon\Carbon;
use Idsqm\Sitemap\SimpleFilesystemWriter;
use Idsqm\Sitemap\SitemapGenerator;

$generator = new SitemapGenerator(
    'https://test.com/',
    [
        ['loc' => 'page/one', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => 0.5],
        ['loc' => 'page/two', 'lastmod' => Carbon::today(), 'changefreq' => 'monthly', 'priority' => 0.6],
        ['loc' => 'another-section'],
    ],
    'xml',
    '/var/www/public/sitemap.xml',
    new SimpleFilesystemWriter(),
);

$generator->export();
```
Example of registering a sitemap exporter in a different format:
```php
<?php

use Idsqm\Sitemap\Sitemap;
use Idsqm\Sitemap\SitemapExporter;
use Idsqm\Sitemap\SitemapExporterFactory;

SitemapExporterFactory::register('new-format', fn() => new class implements SitemapExporter {
    public function execute(Sitemap $sitemap): string
    {
        $result = '';

        foreach ($sitemap->getSitemapRecords() as $sitemapRecord) {
            $result .= $sitemapRecord->getLoc();
        }

        return $result;
    }
});

// Using a new exporter
$generator = new SitemapGenerator(
    'https://test.com/',
    [
        ['loc' => 'page/one']
    ],
    'new-format',
    '/var/www/public/sitemap.format',
    new SimpleFilesystemWriter(),
);

$generator->export();
```

## Run tests
```shell
./vendor/bin/phpunit
```

## Linter
The phpstan static code analyzer is used

Make the script executable:
```shell
chmod +x utils/linter/linter.sh
```

**Run** linter
```shell
./utils/linter/linter.sh
```