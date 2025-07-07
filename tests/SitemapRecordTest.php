<?php

namespace Idsqm\Sitemap\Tests;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\Exceptions\InvalidFormatException;
use Idsqm\Sitemap\Freq;
use Idsqm\Sitemap\SitemapRecord;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ValueError;

class SitemapRecordTest extends TestCase
{
    /**
     * @return array{array{string, CarbonInterface|string|null, Freq|string|null, float|null}}
     */
    public static function constructorDataProvider(): array
    {
        return [
            [
                'https://test.test/page/one',
                '2024-06-05',
                'monthly',
                0.5,
            ],
            [
                'https://test.test/page/two',
                null,
                null,
                null,
            ],
            [
                'https://test.test/page/three',
                Carbon::now(),
                Freq::Always,
                0.1,
            ],
        ];
    }

    /**
     * @return array{array{CarbonInterface|string|null}}
     */
    public static function lastModDataProvider(): array
    {
        return [
            [ null ],
            [ '2024-06-05' ],
            [ Carbon::now() ],
            [ Carbon::today() ],
            [ Carbon::tomorrow() ],
            [ Carbon::now()->addYear() ],
            [ '15.12.2023 14:00' ],
        ];
    }

    /**
     * @return array{array{mixed}}
     */
    public static function incorrectLastModDataProvider(): array
    {
        return [
            [ 'non-parsed date' ],
        ];
    }

    /**
     * @return array{array{Freq|string|null}}
     */
    public static function changeFreqDataProvider(): array
    {
        return [
            [ null ],
            [ Freq::Hourly ],
            [ 'monthly' ],
            [ 'always' ],
            [ 'hourly' ],
            [ 'weekly' ],
            [ 'yearly' ],
            [ 'never' ],
        ];
    }

    /**
     * @return array{array{mixed}}
     */
    public static function incorrectChangeFreqDataProvider(): array
    {
        return [
            [ 'incorrect_freq_value' ],
        ];
    }

    /**
     * @return array{array{float|null}}
     */
    public static function priorityDataProvider(): array
    {
        return [
            [ null ],
            [ 0.0 ],
            [ 0.1 ],
            [ 0.2 ],
            [ 0.3 ],
            [ 0.4 ],
            [ 0.5 ],
            [ 0.6 ],
            [ 0.7 ],
            [ 0.8 ],
            [ 0.9 ],
            [ 1.0 ],
        ];
    }

    /**
     * @return array{array{mixed}}
     */
    public static function incorrectPriorityDataProvider(): array
    {
        return [
            [ 1.1 ],
            [ -0.1 ],
        ];
    }

    #[DataProvider('constructorDataProvider')]
    public function testConstructor(
        string $loc, CarbonInterface|string|null $lastMod, Freq|string|null $changeFreq, float|null $priority
    ): void {
        $record = new SitemapRecord(
            $loc,
            $lastMod,
            $changeFreq,
            $priority,
        );

        $lastMod = $this->getExpectedW3cLastMod($lastMod);
        $changeFreq = $this->getExpectedFreqEnumValue($changeFreq);

        $this->assertEquals($loc, $record->getLoc());
        $this->assertEquals($lastMod, $record->getLastMod());
        $this->assertEquals($priority, $record->getPriority());
        $this->assertEquals($changeFreq, $record->getChangeFreq());
    }

    #[DataProvider('lastModDataProvider')]
    public function testSetLastMod(CarbonInterface|string|null $lastMod): void
    {
        $record = $this->getBaseSitemapRecord();
        $record->setLastMod($lastMod);

        $lastMod = $this->getExpectedW3cLastMod($lastMod);

        $this->assertEquals($lastMod, $record->getLastMod());
    }

    #[DataProvider('changeFreqDataProvider')]
    public function testSetChangeFreq(Freq|string|null $changeFreq): void
    {
        $record = $this->getBaseSitemapRecord();
        $record->setChangeFreq($changeFreq);

        $changeFreq = $this->getExpectedFreqEnumValue($changeFreq);

        $this->assertEquals($changeFreq, $record->getChangeFreq());
    }

    #[DataProvider('priorityDataProvider')]
    public function testSetPriority(float|null $priority): void
    {
        $record = $this->getBaseSitemapRecord();
        $record->setPriority($priority);

        $this->assertEquals($priority, $record->getPriority());
    }

    #[DataProvider('incorrectLastModDataProvider')]
    public function testSetIncorrectLastMod(CarbonInterface|string|null $lastMod): void
    {
        $this->expectException(InvalidFormatException::class);

        $record = $this->getBaseSitemapRecord();
        $record->setLastMod($lastMod);
    }

    #[DataProvider('incorrectChangeFreqDataProvider')]
    public function testSetIncorrectChangeFreq(Freq|string|null $changeFreq): void
    {
        $this->expectException(ValueError::class);

        $record = $this->getBaseSitemapRecord();
        $record->setChangeFreq($changeFreq);
    }

    #[DataProvider('incorrectPriorityDataProvider')]
    public function testSetIncorrectPriority(float|null $priority): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Priority must be between 0 and 1.0");

        $record = $this->getBaseSitemapRecord();
        $record->setPriority($priority);
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

    private function getBaseSitemapRecord(): SitemapRecord
    {
        return new SitemapRecord('https://test.test/page/one');
    }
}