<?php

namespace Idsqm\Sitemap;

interface SitemapExporter
{
    /**
     * @param Sitemap $sitemap
     * @return string Sitemap adapted to the format
     */
    public function execute(Sitemap $sitemap): string;
}