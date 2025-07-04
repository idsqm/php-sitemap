<?php

namespace Idsqm\Sitemap;

interface FilesystemWriter
{
    public function write(string $location, string $contents): void;
}