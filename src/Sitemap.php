<?php

namespace Idsqm\Sitemap;

use InvalidArgumentException;

class Sitemap
{
    public const MAX_SITEMAP_RECORD_COUNT = 50000;

    private string $baseUrl; /* @phpstan-ignore property.onlyWritten */

    /** @var SitemapRecord[] $records */
    private array $records;

    /**
     * @param string $baseUrl
     * @param SitemapRecord[] $records
     * @throws InvalidArgumentException
     */
    public function __construct(string $baseUrl, array $records)
    {
        if (count($records) > self::MAX_SITEMAP_RECORD_COUNT) {
            throw new InvalidArgumentException('Too many sitemap records, max ' . self::MAX_SITEMAP_RECORD_COUNT . '  records');
        }

        $this->baseUrl = $baseUrl;
        $this->records = $records;
    }

    /**
     * @return SitemapRecord[]
     */
    public function getSitemapRecords(): array
    {
        return $this->records;
    }
}