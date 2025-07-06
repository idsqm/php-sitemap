<?php

namespace Idsqm\Sitemap\Tests\Exporters;

use Idsqm\Sitemap\Exporters\CSVExporter;

class CSVExporterTest extends ExporterTestCase
{
    private string $cvsResult = '
loc;lastmod;priority;changefreq
https://test.test/one;2024-05-06T00:00:00+00:00;0.5;monthly
https://test.test/two;2023-12-12T12:30:00+00:00;1;always
https://test.test/three;;;
';

    public function testCSVSitemapExport(): void
    {
        $exporter = new CSVExporter();

        $result = $exporter->execute($this->getSitemap());

        $this->assertEquals($this->removeWhiteSpaces($this->cvsResult), $this->removeWhiteSpaces($result));
    }
}