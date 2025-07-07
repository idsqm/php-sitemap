<?php

namespace Idsqm\Sitemap\Exporters;

use Idsqm\Sitemap\FilesystemWriter;
use Idsqm\Sitemap\Sitemap;
use Idsqm\Sitemap\SitemapExporter;
use Idsqm\Sitemap\SitemapRecord;

class CSVExporter implements SitemapExporter
{
    public function execute(Sitemap $sitemap): string
    {
        $resultText = 'loc;lastmod;priority;changefreq' . PHP_EOL;

        $records = $sitemap->getSitemapRecords();

        foreach ($records as $record) {
            $resultText .= $this->getRecordString($record);
            $resultText .= PHP_EOL;
        }

        return $resultText;
    }

    private function getRecordString(SitemapRecord $record): string
    {
        $loc = $record->getLoc();
        $lastMod = $record->getLastMod();
        $changeFreq = $record->getChangefreq();
        $priority = $record->getPriority();

        $recordString = $loc . ';';
        $recordString .= $lastMod . ';';
        $recordString .= $priority . ';';
        $recordString .= $changeFreq->value ?? '';

        return $recordString;
    }
}