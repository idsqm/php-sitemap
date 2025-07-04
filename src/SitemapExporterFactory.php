<?php

namespace Idsqm\Sitemap;

use Closure;
use Idsqm\Sitemap\Exporters\CSVExporter;
use Idsqm\Sitemap\Exporters\JSONExporter;
use Idsqm\Sitemap\Exporters\XMLExporter;
use InvalidArgumentException;

class SitemapExporterFactory
{
    private static array $exporters = [];

    /**
     * Register new exporter to app
     * @param string $extension
     * @param Closure $closure
     * @return void
     */
    public static function register(string $extension, Closure $closure): void
    {
        self::$exporters[$extension] = $closure;
    }

    public static function unRegister(string $extension): void
    {
        unset(self::$exporters[$extension]);
    }

    /**
     * @param string $extension
     * @return SitemapExporter
     * @throws InvalidArgumentException
     */
    public static function make(string $extension): SitemapExporter
    {
        if (isset(self::$exporters[$extension])) {
            return call_user_func(self::$exporters[$extension]);
        }

        return match ($extension) {
            'xml' => new XMLExporter(),
            'json' => new JSONExporter(),
            'csv' => new CSVExporter(),
            default => throw new InvalidArgumentException('Unsupported sitemap extension: ' . $extension),
        };
    }
}