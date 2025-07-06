<?php

namespace Idsqm\Sitemap\Tests\Exporters;

use Idsqm\Sitemap\Exporters\XMLExporter;

class XMLExporterTest extends ExporterTestCase
{
    private string $xmlResult = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<url>
<loc>https://test.test/one</loc>
<lastmod>2024-05-06T00:00:00+00:00</lastmod>
<priority>0.5</priority>
<changefreq>monthly</changefreq>
</url>
<url>
<loc>https://test.test/two</loc>
<lastmod>2023-12-12T12:30:00+00:00</lastmod>
<priority>1</priority>
<changefreq>always</changefreq>
</url>
<url>
<loc>https://test.test/three</loc>
</url>
</urlset>
';

    public function testXMLSitemapExport(): void
    {
        $exporter = new XMLExporter();

        $result = $exporter->execute($this->getSitemap());

        $this->assertXmlStringEqualsXmlString($this->xmlResult, $result);
    }
}