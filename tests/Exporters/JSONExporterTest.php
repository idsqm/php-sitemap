<?php

namespace Idsqm\Sitemap\Tests\Exporters;

use Idsqm\Sitemap\Exporters\JSONExporter;

class JSONExporterTest extends ExporterTestCase
{
    private string $jsonResult = '
    [
        {
            "loc": "https://test.test/one",
            "lastmod": "2024-05-06T00:00:00+00:00",
            "priority": 0.5,
            "changefreq": "monthly"
        },
        {
            "loc": "https://test.test/two",
            "lastmod": "2023-12-12T12:30:00+00:00",
            "priority": 1,
            "changefreq": "always"
        },
        {
            "loc": "https://test.test/three"
        }
    ]
';

    public function testJsonSitemapExport()
    {
        $exporter = new JSONExporter();

        $result = $exporter->execute($this->getSitemap());

        $this->assertJsonStringEqualsJsonString($this->jsonResult, $result);
    }
}