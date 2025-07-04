<?php

namespace Idsqm\Sitemap;

use InvalidArgumentException;
use RuntimeException;
use Throwable;

class SimpleFilesystemWriter implements FilesystemWriter
{
    public function write(string $location, string $contents): void
    {
        if (file_exists($location)) {
            throw new InvalidArgumentException('File \'' . $location . '\' already exists.');
        }

        try {
            $file = fopen($location, 'x');
        } catch (Throwable $e) {
            throw new RuntimeException('Unable to open file \'' . $location . '\' to write.', previous: $e);
        }

        try {
            fwrite($file, $contents);
        } catch (Throwable $e) {
            throw new RuntimeException('Unable to write \'' . $location . '\'.', previous: $e);
        }

        fclose($file);
    }
}