<?php

namespace Idsqm\Sitemap\Tests;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Idsqm\Sitemap\FilesystemWriter;
use Idsqm\Sitemap\Freq;
use Idsqm\Sitemap\SitemapExporter;
use Idsqm\Sitemap\SitemapExporterFactory;
use Idsqm\Sitemap\SitemapGenerator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TypeError;

class SitemapGeneratorTest extends TestCase
{
    private const URL = 'https://test.test/';

    private const EXTENSION = 'test';

    private const FILENAME = 'test.test';

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

    /**
     * @return array{array{array{array{loc: string, lastmod: Carbon|string|null, changefreq: Freq|string|null, priority: float|null}}}}
     */
    public static function urlsDataProviders(): array
    {
        return [
            [
                [
                    array('loc' => 'one', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => 0.5),
                    array('loc' => 'two', 'lastmod' => date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => 0.6),
                ],
            ],
            [
                [
                    array('loc' => 'three'),
                ]
            ],
        ];
    }

    /**
     * @param array{array{loc: string, lastmod: Carbon|string|null, changefreq: Freq|string|null, priority: float|null}} $urls
     * @return void
     */
    #[DataProvider('urlsDataProviders')]
    public function testSiteMapGenerator(array $urls): void
    {
        $generator = new SitemapGenerator(
            self::URL,
            $urls,
            self::EXTENSION,
            self::FILENAME,
            $this->mockFileSystemWriter()
        );

        $sitemap = $generator->getSitemap();
        $records = $sitemap->getSitemapRecords();

        $resultUrls = [];
        foreach ($urls as $url) {
            $newUrl['loc'] = self::URL . $url['loc'];
            $newUrl['lastmod'] = $this->getExpectedW3cLastMod($url['lastmod'] ?? null);
            $newUrl['changefreq'] = $this->getExpectedFreqEnumValue($url['changefreq'] ?? null);
            $newUrl['priority'] = $url['priority'] ?? null;

            $resultUrls[] = $newUrl;
        }

        foreach ($records as $record) {
            $result = [
                'loc' => $record->getLoc(),
                'lastmod' => $record->getLastMod(),
                'changefreq' => $record->getChangeFreq(),
                'priority' => $record->getPriority(),
            ];

            $this->assertContains($result, $resultUrls);
        }
    }

    public function testSetWrongCallbackExporter(): void
    {
        $this->expectException(TypeError::class);

        /** @phpstan-ignore argument.type */
        $generator = new SitemapGenerator(self::URL, [], self::EXTENSION, self::FILENAME, $this->mockFileSystemWriter());

        $generator->setExporter(fn() => '');
    }

    public function testSetWrongExporterExtension(): void
    {
        $extension = 'wrong extension';

        $this->expectException(invalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported sitemap extension: ' . $extension);

        /** @phpstan-ignore argument.type */
        $generator = new SitemapGenerator(self::URL, [], self::EXTENSION, self::FILENAME, $this->mockFileSystemWriter());

        $generator->setExporter($extension);
    }

    private function mockFileSystemWriter(): FilesystemWriter
    {
        return $this->createMock(FilesystemWriter::class);
    }

    private function mockExporter(): SitemapExporter
    {
        $exporter = $this->createMock(SitemapExporter::class);
        $exporter->method('execute')
            ->willReturn('test output text');

        return $exporter;
    }

    private function getExpectedW3cLastMod(CarbonInterface|string|null $lastMod): ?string
    {
        if ($lastMod !== null) {
            $lastMod = Carbon::parse($lastMod);
            return $lastMod->toW3cString();
        }

        return null;
    }

    private function getExpectedFreqEnumValue(Freq|string|null $changeFreq): ?Freq
    {
        if (is_string($changeFreq)) {
            $changeFreq = Freq::from($changeFreq);
        }

        return $changeFreq;
    }
}
