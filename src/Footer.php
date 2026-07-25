<?php

// story: e01s01

declare(strict_types=1);

namespace App;

final class Footer
{
    public static function render(string $versionFilePath): string
    {
        $version = trim((string) file_get_contents($versionFilePath));
        return "<h1>bigbase canary (PHP)</h1><footer>v{$version}</footer>";
    }
}
