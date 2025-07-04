<?php

namespace Idsqm\Sitemap;

use InvalidArgumentException;
use TypeError;

class SitemapGenerator
{
    private string $baseUrl;

    private Sitemap $sitemap;

    private string $outputFilePath;

    private SitemapExporter $exporter;

    private FilesystemWriter $filesystemWriter;

    /**
     * @param string $baseUrl
     * @param array $urls
     * @param string $extension
     * @param string $outputFilePath
     * @param FilesystemWriter $fsWriter
     */
    public function __construct(
        string $baseUrl,
        array $urls,
        string $extension,
        string $outputFilePath,
        FilesystemWriter $fsWriter,
    ) {
        $this->baseUrl = $baseUrl;
        $this->sitemap = $this->generateSitemap($urls);
        $this->outputFilePath = $outputFilePath;
        $this->exporter = SitemapExporterFactory::make($extension);
        $this->filesystemWriter = $fsWriter;
    }

    /**
     * @param string|null $location
     * @return void
     */
    public function export(?string $location = null): void
    {
        $location = $location ?? $this->outputFilePath;
        $fileData = $this->exporter->execute($this->sitemap);

        $this->filesystemWriter->write($location, $fileData);
    }

    /**
     * @param SitemapExporter|callable|string $exporter
     * @return void
     * @throws InvalidArgumentException|TypeError
     */
    public function setExporter(SitemapExporter|callable|string $exporter): void
    {
        if ($exporter instanceof SitemapExporter) {
            $this->exporter = $exporter;
            return;
        }

        if (is_callable($exporter)) {
            $this->exporter = call_user_func($exporter);
            return;
        }

        if (is_string($exporter)) {
            $this->exporter = SitemapExporterFactory::make($exporter);
        }

        throw new InvalidArgumentException('Wrong sitemap exporter argument');
    }

    /**
     * @return Sitemap
     */
    public function getSitemap(): Sitemap
    {
        return $this->sitemap;
    }

    /**
     * @param array $urls
     * @return Sitemap
     * @throws InvalidArgumentException
     */
    private function generateSitemap(array $urls): Sitemap
    {
        $sitemapRecords = [];

        foreach ($urls as $url) {
            $sitemapRecords[] = new SitemapRecord(
                $this->baseUrl . $url['loc'],
                $url['lastmod'] ?? null,
                $url['changefreq'] ?? null,
                $url['priority'] ?? null
            );
        }

        return new Sitemap($this->baseUrl, $sitemapRecords);
    }
}