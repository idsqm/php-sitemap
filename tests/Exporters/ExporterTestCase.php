<?php

namespace Idsqm\Sitemap\Tests\Exporters;

use Idsqm\Sitemap\Sitemap;
use Idsqm\Sitemap\SitemapRecord;
use PHPUnit\Framework\TestCase;

class ExporterTestCase extends TestCase
{
    protected const URL = 'https://test.test/';

    protected function getSitemap(): Sitemap
    {
        return new Sitemap(self::URL, [
            new SitemapRecord(self::URL . 'one', '2024-05-06', 'monthly', 0.5),
            new SitemapRecord(self::URL . 'two', '2023-12-12 12:30', 'always', 1),
            new SitemapRecord(self::URL . 'three'),
        ]);
    }

    protected function removeWhiteSpaces(string $string): string
    {
        return preg_filter('/\s+/', '', $string);
    }
}