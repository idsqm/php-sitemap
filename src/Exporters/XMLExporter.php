<?php

namespace Idsqm\Sitemap\Exporters;

use Idsqm\Sitemap\Sitemap;
use Idsqm\Sitemap\SitemapExporter;
use Idsqm\Sitemap\SitemapRecord;

class XMLExporter implements SitemapExporter
{
    public function execute(Sitemap $sitemap): string
    {
        $resultText = '';
        $records = $sitemap->getSitemapRecords();

        $resultText .= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $resultText .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;

        foreach ($records as $record) {
            $resultText .= '<url>' . PHP_EOL;
            $resultText .= $this->getRecordString($record);
            $resultText .= '</url>' . PHP_EOL;
        }

        $resultText .= '</urlset>' . PHP_EOL;
        return $resultText;
    }

    private function getRecordString(SitemapRecord $record): string
    {
        $loc = $record->getLoc();
        $lastMod = $record->getLastMod();
        $changeFreq = $record->getChangefreq();
        $priority = $record->getPriority();

        $recordString = '<loc>' . $loc . '</loc>' . PHP_EOL;

        if ($lastMod !== null) {
            $recordString .= '<lastmod>' . $lastMod . '</lastmod>' . PHP_EOL;
        }

        if ($priority !== null) {
            $recordString .= '<priority>' . $priority . '</priority>' . PHP_EOL;
        }

        if ($changeFreq !== null) {
            $recordString .= '<changefreq>' . $changeFreq->value . '</changefreq>' . PHP_EOL;
        }

        return $recordString;
    }
}