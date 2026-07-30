<?php

// story: e01s01

declare(strict_types=1);

namespace App;

final class Footer
{
    public static function render(string $versionFilePath): string
    {
        if (!is_readable($versionFilePath)) {
            throw new \RuntimeException("VERSION file not found or not readable: {$versionFilePath}");
        }

        $version = trim((string) file_get_contents($versionFilePath));

        if ($version === '') {
            throw new \RuntimeException("VERSION file is empty: {$versionFilePath}");
        }

        return "<h1>bigbase canary (PHP)</h1><footer>v{$version}</footer>";
    }
}
