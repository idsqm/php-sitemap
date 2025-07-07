<?php

namespace Idsqm\Sitemap\Tests;

use Idsqm\Sitemap\Sitemap;
use Idsqm\Sitemap\SitemapExporter;
use Idsqm\Sitemap\SitemapExporterFactory;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TypeError;

class SitemapExporterFactoryTest extends TestCase
{
    private const EXTENSION = 'test';

    private const TEST_EXPORTER_TEXT = 'test exporter text';

    protected function setUp(): void
    {
        parent::setUp();
        SitemapExporterFactory::register(self::EXTENSION, fn() => $this->mockExporter());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        SitemapExporterFactory::unRegister(self::EXTENSION);
    }

    public function testRegisteredExporter(): void
    {
        $exporter = SitemapExporterFactory::make(self::EXTENSION);

        $this->assertEquals(self::TEST_EXPORTER_TEXT, $exporter->execute($this->mockSitemap()));
    }

    public function testMakeUnsupportedExporter(): void
    {
        $unsupportedExporter = 'unsupported';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported sitemap extension: ' . $unsupportedExporter);

        SitemapExporterFactory::make($unsupportedExporter);
    }

    public function testMakeWrongCallbackExporter(): void
    {
        $this->expectException(TypeError::class);

        $wrongExtension = 'wrong';
        SitemapExporterFactory::register($wrongExtension, fn() => '');

        SitemapExporterFactory::make($wrongExtension);
    }

    private function mockExporter(): SitemapExporter
    {
        $exporter = $this->createMock(SitemapExporter::class);
        $exporter->method('execute')
            ->willReturn(self::TEST_EXPORTER_TEXT);

        return $exporter;
    }

    private function mockSitemap(): Sitemap
    {
        return $this->createMock(Sitemap::class);
    }
}
