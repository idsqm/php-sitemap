<?php

namespace Idsqm\Sitemap\Tests;

use Idsqm\Sitemap\Sitemap;
use Idsqm\Sitemap\SitemapRecord;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SitemapTest extends TestCase
{
    private const URL = 'https://test.test/';

    /**
     * @return array{array{SitemapRecord[]}}
     */
    public static function recordDataProvider(): array
    {
        return [
            [
                array(),
            ],
            [
                array(
                    new SitemapRecord(self::URL . '/one'),
                ),
            ],
            [
                array(
                    new SitemapRecord(self::URL . '/one'),
                    new SitemapRecord(self::URL . '/two'),
                ),
            ],
        ];
    }

    /**
     * @param SitemapRecord[] $records
     * @return void
     */
    #[DataProvider('recordDataProvider')]
    public function testConstructor(array $records): void
    {
        $sitemap = new Sitemap(self::URL, $records);

        $this->assertEquals($records, $sitemap->getSitemapRecords());
    }

    public function testMaxLimitOfRecords(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Too many sitemap records, max ' . Sitemap::MAX_SITEMAP_RECORD_COUNT . '  records');

        $records = array_fill(0, Sitemap::MAX_SITEMAP_RECORD_COUNT + 1, ' ');

        /** @phpstan-ignore argument.type */
        new Sitemap(self::URL, $records);
    }
}