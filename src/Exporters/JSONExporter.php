<?php

namespace Idsqm\Sitemap\Exporters;

use Idsqm\Sitemap\Sitemap;
use Idsqm\Sitemap\SitemapExporter;
use Idsqm\Sitemap\SitemapRecord;

class JSONExporter implements SitemapExporter
{
    public function execute(Sitemap $sitemap): string
    {
        $resultText = '[';

        $records = $sitemap->getSitemapRecords();

        foreach ($records as $record) {
            $resultText .= '{' . PHP_EOL;
            $resultText .= $this->getRecordString($record);
            $resultText .= '},' . PHP_EOL;
        }

        $resultText = rtrim($resultText, PHP_EOL . ',');
        $resultText .= ']';

        return $resultText;
    }

    private function getRecordString(SitemapRecord $record): string
    {
        $loc = $record->getLoc();
        $lastMod = $record->getLastMod();
        $changeFreq = $record->getChangefreq();
        $priority = $record->getPriority();

        $recordString = '"loc": "' . $loc . '",' . PHP_EOL;

        if ($lastMod !== null) {
            $recordString .= '"lastmod": "' . $lastMod . '",' . PHP_EOL;
        }

        if ($changeFreq !== null) {
            $recordString .= '"changefreq": "' . $changeFreq->value . '",' . PHP_EOL;
        }

        if ($priority !== null) {
            $recordString .= '"priority": ' . $priority  . ',' .  PHP_EOL;
        }

        return rtrim($recordString, PHP_EOL . ',');
    }
}